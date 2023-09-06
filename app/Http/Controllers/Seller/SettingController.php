<?php

namespace App\Http\Controllers\Seller;

use App\Classes\Helper\CommonUtil;
use App\Classes\Helper\StripeConnect;
use App\Http\Controllers\Controller;
use App\Http\Requests\AccountRequest;
use App\Http\Requests\CancelPlanRequest;
use App\Http\Requests\StripeCardRequest;
use App\Models\AdminCoupon;
use App\Models\Notification;
use App\Models\Page;
use App\Models\Plan;
use App\Models\StripeCard;
use App\Models\StripeSubscription;
use App\Models\User;
use App\Models\UserPlan;
use App\Models\Contact;
use App\Models\UserPlanChange;
use Carbon\Carbon;
use File;
use Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use MetaTag;
use Session;
use App\Enums\EventActive;
use DB;

class SettingController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * View Account Tab
     *
     * @return View
     */
    public function account()
    {
        $user = Auth::User();
        MetaTag::set('title', config('app.rankup.company_title')." - " . $user->name);
        MetaTag::set('description', isset($user->description) ? config('app.rankup.company_title')." - " . $user->description : config('app.rankup.company_title')." Account Page");
        MetaTag::set('image', isset($user->profile_image) ? asset('storage/' . $user->profile_image) : asset(config('app.rankup.company_logo_path')));
        return View::make('seller.setting.account', compact('user'));
    }

    /**
     * Check Notification
     *
     * @return View
     */
    public function notification()
    {
        MetaTag::set('title', config('app.rankup.company_title')." - ".__('Notifications'));
        MetaTag::set('description', config('app.rankup.company_title').' Notifications Page');
        MetaTag::set('image', asset(config('app.rankup.company_logo_path')));
        $notification = Notification::IsUser()->first();
        if (!$notification) {
            $notification = Notification::create(['user_id' => Auth::user()->id]);
            $notification = Notification::IsUser()->first();
        }
        $user = Auth::user();
        return View::make('seller.setting.notification', compact('notification', 'user'));
    }

    /**
     * Save Notification status
     *
     * @param Request $request
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function saveNotification(Request $request, $id)
    {
        $data = $request->all();
        $notification = Notification::find($id);

        $data['is_notification'] = isset($data['is_notification']) ? 1 : 0;

        if ($notification->update($data)) {
            Session::flash('success', __('Notification has been updated!'));
            return redirect()->back();
        }
        return redirect()->back();
    }

    /**
     * View Terms And Policy Tab
     *
     * @return View
     */
    public function termsAndPolicy()
    {
        MetaTag::set('title', config('app.rankup.company_title')." - ".__('Terms And Privacy'));
        MetaTag::set('description', config('app.rankup.company_title').' Terms And Privacy Page');
        MetaTag::set('image', asset(config('app.rankup.company_logo_path')));
        $page = Page::where(['page_id' => 'TERMS_AND_POLICY'])->first();
        return View::make('seller.setting.terms-and-policy', compact('page'));
    }

    /**
     * View Subscription Tab
     *
     * @return View
     */
    public function mySubscription()
    {
        MetaTag::set('title', config('app.rankup.company_title')." - ".__('My subscription'));
        MetaTag::set('description', config('app.rankup.company_title').' My Subscription Page');
        MetaTag::set('image', asset(config('app.rankup.company_logo_path')));
        if (empty(config('services.stripe.secret'))) {
            abort(403, 'Please configure stripe payment');
        }

        if(config('app.rankup.comapny_name') == "ibuumerang_rankup") {
            return redirect()->route('seller-dashboard');
        }
        $cards = Auth::user()->stripeCards;
        $myPlan = UserPlan::getMyplan();
        $isPlanActive = false;
        if(!empty($myPlan)) {
            $isPlanActive = UserPlan::isPlanActive($myPlan);
        }
        $incomplete = false;
        $payUrl = "";
        if ($myPlan && $isPlanActive && $myPlan->stripe_status == 'incomplete') {
            $incomplete = true;
            $stripeSubscriptoin = $myPlan->stripeSubscription;
            if ($stripeSubscriptoin && $stripeSubscriptoin->stripe_object) {
                $stripeObject = json_decode($stripeSubscriptoin->stripe_object);
                $invoice = StripeConnect::getInvoice($stripeObject->latest_invoice);
                if ($invoice && $invoice['status'] != 'paid' && !empty($invoice['hosted_invoice_url'])) {
                    $payUrl = $invoice['hosted_invoice_url'];
                } else {
                    $incomplete = false;
                }
            }
        }
        $myPlanSlug = $myPlan && $isPlanActive ? $myPlan->plan->slug : '';
        $userHasPlan= isset(Auth::user()->userPlan) && !empty(Auth::user()->userPlan) ? true : false;
        $freePlan = Plan::findOrfail(Plan::FREE_PLAN_ID);
        $standardPlan = Plan::where(['is_active' => 1, 'slug' => 'standard'])->first();
        $standardMonthPlan = Plan::where(['is_active' => 1, 'slug' => 'pro_month'])->first();
        $proPlan = Plan::where(['is_active' => 1, 'slug' => 'pro_year_199'])->first();
        $proBiYearPlan = Plan::where(['is_active' => 1, 'slug' => 'pro_bi_year'])->first();
        $now = getCarbonNowForUser();
        $latestContacts = Contact::where('user_id', Auth::User()->id)->latest()->take(2)->get();

        return View::make('seller.setting.subscription.index', compact('cards', 'myPlan', 'freePlan', 'standardPlan', 'proPlan', 'myPlanSlug', 'payUrl', 'incomplete', 'standardMonthPlan', 'isPlanActive', 'proBiYearPlan', 'userHasPlan', 'latestContacts'));
    }

    /**
     * Update account
     *
     * @param AccountRequest $request
     * @param int $id
     *
     * @return View
     */
    public function accountUpdate(AccountRequest $request, $id)
    {
        $data = $request->all();
        $user = User::find($id);
        request()->validate(
            array_merge(
                array(
                    'email' => 'unique:users,email,' . $id,
                )
            )
        );

        if (request()->has('current_password')) {
            request()->validate(
                array_merge(
                    array(
                        'new_password' => 'required',
                        'confirm_password' => 'required|same:new_password',
                    )
                ),
                array_merge(
                    array(
                        'new_password.required' => __("The new password field is required."),
                        'confirm_password.required' => __("The confirm password field is required."),
                        'confirm_password.same' => __("The confirm password and password must match."),
                    )
                )
            );
            if (Hash::check($data['current_password'], $user->password)) {
                $data['password'] = Hash::make($data['new_password']);
            } else {
                request()->validate(
                    array_merge(
                        array(
                            'current_password' => 'same:password',
                        )
                    )
                );
            }
        }

        if ($request->hasFile('profile_image')) {
            if (isset($user->profile_image)) {
                CommonUtil::removeFile($user->profile_image);
            }
            if(isset($user->thumbnail_image)) {
                CommonUtil::removeFile($user->thumbnail_image);
            }
            $imageName = CommonUtil::uploadFileToFolder($request->file('profile_image'), 'users/image');
            $data['profile_image'] = $imageName;
            $thumbanilImage = CommonUtil::generateThumbnails($request->file('profile_image'), 'users/thumbnails');
            $data['thumbnail_image'] = $thumbanilImage;
        }
        $user->update($data);
        Session::flash('success', __('Account has been updated!'));
        return redirect()->route('seller.setting.account');
    }

    /**
     * Delete profile image
     *
     * @return View
     */
    public function deleteImage()
    {
        $user = Auth::User();
        if (isset($user->profile_image)) {
            CommonUtil::removeFile($user->profile_image);
        }
        if(isset($user->thumbnail_image)) {
            CommonUtil::removeFile($user->thumbnail_image);
        }
        $user->profile_image = null;
        $user->thumbnail_image = null;
        $user->save();
        return redirect()->back();
    }

    /**
     * Create customer card
     *
     * @param StripeCardRequest $request
     *
     * @return Response
     */
    public function cardAdd(StripeCardRequest $request)
    {
        $data = $request->all();
        $user = Auth::user();

        // Create stripe customer
        $response = $user->createStripeCustomerIfNull();
        if ($response['res_status'] == 'error') {
            return response()->json([
                'success' => false,
                'error_msg' => __($response['message']),
            ], 422);
        }

        $expiry_date = explode('/', $request->expiry_date);

        $stripeCardData = [];
        $stripeCardData['name'] = $request->card_holder_name;
        $stripeCardData['number'] = preg_replace('/\s+/', '', $request->card_number);
        $stripeCardData['exp_month'] = $expiry_date[0];
        $stripeCardData['exp_year'] = $expiry_date[1];
        $stripeCardData['cvc'] = $request->cvv;

        $stripeData = StripeConnect::createCard($user->stripe_customer_id, $stripeCardData);
        if ($stripeData['res_status'] == 'success') {
            $isActive = $user->activeStripeCard ? 0 : 1;

            $data = [
                'user_id' => $user->id,
                'stripe_id' => $stripeData['id'],
                'card_holder_name' => $request->card_holder_name,
                'card_last_four' => $stripeData['last4'],
                'card_brand' => '',
                'card_expiry_date' => $request->expiry_date,
                'is_active' => $isActive,
            ];
            StripeCard::create($data);
            return response()->json([
                'success' => true,
                'redirect_url' => route('seller.setting.notification'),
            ], 200);

        } else {
            return response()->json([
                'success' => false,
                'error_msg' => __($stripeData['message']),
            ], 422);
        }
    }

    /**
     * Activate Card
     *
     * @param Request $request
     * @param int $card
     *
     * @return Response
     */
    public function activateCard(Request $request, $card)
    {
        StripeCard::where(['user_id' => Auth::id()])->update(['is_active' => 0]);
        $stripeCard = StripeCard::where(['id' => $card, 'user_id' => Auth::id()])->firstOrFail();
        $stripeCard->is_active = 1;

        $updateStripe = StripeConnect::updateCustomer(Auth::user()->stripe_customer_id, ['default_source' => $stripeCard->stripe_id]);
        if ($updateStripe['res_status'] == 'error') {
            return response()->json([
                'errors' => [
                    'card' => __('Unable to set default card.'),
                ],
            ], 200);
        }
        $stripeCard->save();
        return response()->json([
            'success' => true,
        ], 200);
    }

    /**
     * Update Current Plan
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function updatePlan(Request $request)
    {
        $message = __('Plan selected successfully');
        if (empty($request->selected_plan)) {
            return response()->json([
                'errors' => [
                    'plan' => [__('Please select at least one plan')],
                ],
            ], 422);
        }
        $selectedPlan = Plan::findOrFail($request->selected_plan);
        $response = Auth::user()->createStripeCustomerIfNull();
        if ($response['res_status'] == 'error') {
            return response()->json([
                'errors' => [
                    'message' => $response['message'],
                    'plan' => [$response['message']],
                ],
            ], 422);
        }

        $historyCount = UserPlanChange::query()->where(['user_id' => Auth::id()])->where('created_at', '>=', Carbon::now()->subHours(24))->count();
        if ($historyCount >= 3) {
            return response()->json([
                'errors' => [
                    'plan' => [__('You already changed your plan 3 times in last 24 hours. Please try later.')],
                ],
            ], 422);
        }

        $user = Auth::User();
        $activeCard = $user->activeStripeCard;
        if (!$activeCard) {
            return response()->json([
                'errors' => [
                    'plan' => [__('Card is required.')],
                ],
            ], 422);
        }

        $myPlan = UserPlan::getMyplan();
        $isPlanActive = false;
        if(!empty($myPlan)) {
            $isPlanActive = UserPlan::isPlanActive($myPlan);
        }

        $freeTrialDays = NULL;
        if ($request->plan_coupon) {
            $adminCoupon = AdminCoupon::findCoupon($request->plan_coupon);
            if($adminCoupon){
                if($adminCoupon->isCouponAlreadyUsed()){
                    return response()->json([
                        'errors' => [
                            'plan' => [__('You have used this coupon already.')],
                        ],
                    ], 422);
                } else {
                    $adminCouponId = $adminCoupon->id;
                    $freeTrialDays = $adminCoupon->free_trial_days;
                }
            } else {
                $stripePromo = StripeConnect::getPromocode($request->plan_coupon);
                if (empty($stripePromo) || empty($stripePromo['data']) || $stripePromo['data'][0]['coupon']['valid'] !== true) {
                    return response()->json([
                        'errors' => [
                            'plan' => [__('Invalid coupon code.')],
                        ],
                    ], 422);
                }
                $stripePromo = $stripePromo['data'][0];
            }

        }

        if ($myPlan && $isPlanActive && $myPlan->plan_id !== Plan::FREE_PLAN_ID) {
            //Here expiration date is same as active plan, Just plan will be updated with Pro-rata
            $expiration = Carbon::createFromFormat('Y-m-d H:i:s', $myPlan->expiration);
        } else {
            $expiration = Carbon::today()->endOfDay()->addMonth();
        }

        if ($myPlan && $isPlanActive && $myPlan->plan_id !== Plan::FREE_PLAN_ID && $selectedPlan->id !== Plan::TWO_YEAR_PLAN_ID) {
            if($freeTrialDays){
                return response()->json([
                    'errors' => [
                        'plan' => [__('Can not use this coupon to update plan.')],
                    ],
                ], 422);
            }

            $subscriptionOld = $myPlan->stripeSubscription;
            if(isset($subscriptionOld)) {
                $stripeSubscription = StripeConnect::updateSubscription($subscriptionOld->stripe_id, $subscriptionOld->item, $selectedPlan->stripe_price_id, isset($stripePromo) ? $stripePromo->id : null);
            } else {
                $stripeSubscription = StripeConnect::createSubscription(Auth::user()->stripe_customer_id, $selectedPlan->stripe_price_id, isset($stripePromo) ? $stripePromo->id : null, $freeTrialDays);
            }

            if ($stripeSubscription['res_status'] == 'error') {
                return response()->json([
                    'errors' => [
                        'message' => $stripeSubscription['message'],
                        'plan' => [$stripeSubscription['message']],
                    ],
                ], 422);
            }
            $subscriptionNew = $myPlan->stripeSubscription;
            $stripeSubscription['promo'] = $request->plan_coupon ?: null;
            $stripeSubscription['plan_id'] = $selectedPlan->id;
            if(isset($subscriptionOld)) {
                $subscription = StripeSubscription::updateSubscription($subscriptionOld, $stripeSubscription);
            } else {
                $subscription = StripeSubscription::createSubscription(Auth::id(), $stripeSubscription);
            }

            $myPlan->plan_id = $selectedPlan->id;
            $myPlan->stripe_subscription_id = $subscription->id;
            $myPlan->stripe_status = $stripeSubscription['status'];
            $myPlan->expiration = Carbon::createFromTimestamp($stripeSubscription->current_period_end)->format('Y-m-d H:i:s');
            $myPlan->save();
        } else {
            $cancelSubscription = $myPlan->stripeSubscription;
            if ($selectedPlan->id != Plan::TWO_YEAR_PLAN_ID) {
                if(isset($cancelSubscription)) {
                    $cancelStripe = StripeConnect::cancelSubscription($cancelSubscription->stripe_id);
                    if ($cancelStripe['res_status'] != 'error') {
                        $cancelSubscription->status = 'canceled';
                        $cancelSubscription->save();
                    }
                }

                $stripeSubscription = StripeConnect::createSubscription(Auth::user()->stripe_customer_id, $selectedPlan->stripe_price_id, isset($stripePromo) ? $stripePromo->id : null, $freeTrialDays);
                if ($stripeSubscription['res_status'] == 'error') {
                    return response()->json([
                        'errors' => [
                            'message' => $stripeSubscription['message'],
                            'plan' => [$stripeSubscription['message']],
                        ],
                    ], 422);
                }

                $stripeSubscription['promo'] = $request->plan_coupon ?: null;
                $stripeSubscription['admin_coupon_id'] = $adminCouponId ?? null;
                $stripeSubscription['plan_id'] = $selectedPlan->id;
                $subscription = StripeSubscription::createSubscription(Auth::id(), $stripeSubscription);

                $myPlan->stripe_subscription_id = $subscription->id;
                $myPlan->stripe_status = $stripeSubscription['status'];
                $myPlan->status = 'active';
                $myPlan->expiration = Carbon::createFromTimestamp($stripeSubscription->current_period_end)->toDateTimeString();
            } else {
                $amount = $selectedPlan->price * 100;
                $planAmount = round($amount, 2);
                $getCharge = StripeConnect::getCharges($activeCard->stripe_id, $user->stripe_customer_id, $planAmount, 'Two Year Subscription Updated');
                if ($getCharge['res_status'] == 'error') {
                    return response()->json([
                        'errors' => [
                            'message' => $getCharge['message'],
                            'plan' => [$getCharge['message']],
                        ],
                    ], 422);
                }

                if (isset($getCharge['res_status']) && $getCharge['res_status'] == 'success') {
                    $stripePaymentIntentId = $getCharge['id'];
                    $redirect_url = route('plan.payment.confirmation', [$selectedPlan->id, $user->id, $stripePaymentIntentId]);
                    $confirmPaymentIntent = StripeConnect::confirmPaymentIntent($stripePaymentIntentId, $activeCard->stripe_id, $redirect_url);
                    if ($confirmPaymentIntent['res_status'] == 'error') {
                        return response()->json([
                            'errors' => [
                                'message' => $confirmPaymentIntent['message'],
                                'plan' => [$confirmPaymentIntent['message']],
                            ],
                        ], 422);
                    }

                    $afterTwoYears = Carbon::now()->addYears(2)->format('Y-m-d H:i:s');
                    $myPlan->stripe_subscription_id = null;
                    if(isset($confirmPaymentIntent['status']) && $confirmPaymentIntent['status'] == 'requires_action') {
                        $myPlan->expiration = $afterTwoYears;
                        $myPlan->stripe_payment_intent_id = $stripePaymentIntentId;
                        $myPlan->stripe_status = 'incomplete';
                        $myPlan->status = 'expired';
                        $myPlan->plan_id = $selectedPlan->id;
                        $myPlan->save();

                        if(isset($cancelSubscription)) {
                            $cancelStripe = StripeConnect::cancelSubscription($cancelSubscription->stripe_id);
                            if ($cancelStripe['res_status'] != 'error') {
                                $cancelSubscription->status = 'canceled';
                                $cancelSubscription->save();
                            }
                        }

                        return response()->json([
                            'success' => 'requires_action',
                            'redirect_to_url' => $confirmPaymentIntent->next_action->redirect_to_url->url
                        ], 200);
                    }

                    if ($confirmPaymentIntent['res_status'] == 'success' && isset($confirmPaymentIntent['status']) && $confirmPaymentIntent['status'] == 'succeeded') {
                        if(isset($cancelSubscription)) {
                            $cancelStripe = StripeConnect::cancelSubscription($cancelSubscription->stripe_id);
                            if ($cancelStripe['res_status'] != 'error') {
                                $cancelSubscription->status = 'canceled';
                                $cancelSubscription->save();
                            }
                        }

                        $myPlan->expiration = $afterTwoYears;
                        $myPlan->stripe_payment_intent_id = $stripePaymentIntentId;
                        $myPlan->stripe_status = 'active';
                        $myPlan->status = 'active';
                    } else {
                        return response()->json([
                            'errors' => [
                                'message' => __('Plan selection is failed'),
                                'plan' => [__('Plan selection is failed')],
                            ],
                        ], 422);
                    }
                }
            }
            $myPlan->plan_id = $selectedPlan->id;
            $myPlan->save();
            if ($myPlan->status == 'active') {
                UserPlanChange::create([
                    'user_id' => Auth::id(),
                    'plan_id' => $selectedPlan->id,
                    'expiration' => $myPlan->expiration,
                ]);
            }
        }

        Session::flash('success', $message);
        return response()->json([
            'success' => true,
            'message' => $message,
        ], 200);
    }

    /**
     * Check Validate Coupon
     *
     * @param Request $request
     *
     * @return Response
     */
    public function validateCoupon(Request $request)
    {
        if ($request->plan_coupon) {
            $adminPromo = AdminCoupon::findCoupon($request->plan_coupon);
            if($adminPromo){
                if($adminPromo->isCouponAlreadyUsed()){
                    return response()->json([
                        'errors' => [
                            'message' => __('You have used this coupon already.'),
                            'plan_coupon' => [__('You have used this coupon already.')],
                        ],
                    ], 422);
                }
                $trialDays = $adminPromo->free_trial_days;
                $message = 'Coupon Applied Successfully. ';
                $message .= __('got_free_trial', ['days' => $trialDays]);

                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'coupon' => [
                        'amount_off' => 0,
                        'percent_off' => 0,
                        'currency' => 'USD',
                        'duration' => 'once',
                        'duration_in_months' => 0,
                        'name' => $adminPromo->code,
                    ],
                ], 200);
            }

            $stripePromo = StripeConnect::getPromocode($request->plan_coupon);
            if (empty($stripePromo) || empty($stripePromo['data']) || $stripePromo['data'][0]['coupon']['valid'] !== true) {
                return response()->json([
                    'errors' => [
                        'message' => __('The given data was invalid'),
                        'plan_coupon' => [__('Invalid coupon code.')],
                    ],
                ], 422);
            } else {
                $stripePromo = $stripePromo['data'][0];
                $stripeCoupon = $stripePromo['coupon'];
                $message = __('Coupon Applied Successfully. ');
                $message .= __('You will get, ');
                if ($stripeCoupon['amount_off']) {
                    $message .= __('discount.amount', ['amount' => round($stripeCoupon['amount_off'] / 100, 2), 'currency' => strtoupper($stripeCoupon['currency'])]);
                }
                if ($stripeCoupon['percent_off']) {
                    $message .= __('discount.percent', ['percent' => $stripeCoupon['percent_off']]);
                }
                if ($stripeCoupon['duration'] == 'once' || $stripeCoupon['duration'] == 'repeating') {
                    $message .= __('discount.time', ['time' => $stripeCoupon['duration'] == 'once' ? 1 : $stripeCoupon['duration_in_months']]);
                }
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'coupon' => [
                        'amount_off' => round($stripeCoupon['amount_off'] / 100, 2),
                        'percent_off' => $stripeCoupon['percent_off'],
                        'currency' => strtoupper($stripeCoupon['currency']),
                        'duration' => $stripeCoupon['duration'],
                        'duration_in_months' => $stripeCoupon['duration_in_months'],
                        'name' => $stripeCoupon['name'],
                    ],
                ], 200);
            }
        }
        return response()->json([
            'errors' => [
                'message' => __('The given data was invalid'),
                'plan_coupon' => [__('Invalid coupon code.')],
            ],
        ], 422);
    }

    /**
     * Show Banner Video
     *
     * @param Request $request
     *
     * @return data
     */
    public function showBannerVideo(Request $request)
    {
        switch ($request->url) {
            case '/seller/dashboard':
                $data['url'] = asset('training_videos/tableau_de_bord.mp4');
                $data['name'] = __('Dashboard');
                break;
            case '/seller/contacts':
                $data['url'] = asset('training_videos/contacts.mp4');
                $data['name'] = __('Contacts');
                break;
            case '/seller/events':
                $data['url'] = asset('training_videos/evenements.mp4');
                $data['name'] = __('Events');
                break;
            case '/seller/videos':
                $data['url'] = asset('training_videos/formations.mp4');
                $data['name'] = __('Trainings');
                break;
            case '/seller/documents':
                $data['url'] = asset('training_videos/documents.mp4');
                $data['name'] = __('Documents');
                break;
            case '/seller/members':
                $data['url'] = asset('training_videos/membres.mp4');
                $data['name'] = __('Members');
                break;
            case '/seller/analytics':
                $data['url'] = asset('training_videos/statistiques.mp4');
                $data['name'] = __('Analytics');
                break;
            case '/seller/leaderboard':
                $data['url'] = asset('training_videos/classements.mp4');
                $data['name'] = __('Leaderboards');
                break;
            default:
                $data['url'] = asset('training_videos/tableau_de_bord.mp4');
                $data['name'] = __('Dashboard');
                break;
        }
        return $data;
    }

    /**
     * Cancel Plan
     *
     * @param CancelPlanRequest $request
     *
     * @return Response
     */
    public function cancelPlan(CancelPlanRequest $request)
    {
        $data = $request->all();
        $plan = UserPlan::find($request->plan_id);
        $subscription = $plan->stripeSubscription;
        $plan->stripe_status = 'canceled';
        if(isset($subscription) && isset($subscription->stripe_id)) {
            $cancelStripe = StripeConnect::cancelSubscription($subscription->stripe_id);
            if ($cancelStripe['res_status'] == 'error') {
                return response()->json([
                    'success' => false,
                    'error_msg' => $cancelStripe['message']
                ], 200);
            }
            $subscription->status = 'canceled';
            $subscription->save();
            $plan->stripe_status = $cancelStripe['status'];
        }

        $plan->status = 'expired';
        $plan->reason = $request->reason;
        $plan->user_cancel_plan_reason = $request->user_cancel_plan_reason;
        $plan->save();

        Session::flash('success', __('Plan canceled successfully'));
        return response()->json([
            'success' => true,
        ], 200);
    }

    /**
     * Extend free month plan
     *
     * @param Request $request
     *
     * @return Response
     */
    public function freeMonthPlan(Request $request)
    {
        $plan = UserPlan::find($request->plan_id);
        if($plan) {
            $plan->expiration = date('Y-m-d H:i:s', strtotime("+1 months", strtotime($plan->expiration)));
            $plan->is_extend_subscription_plan = 1;
            $plan->update();

            Session::flash('success', __('Your subscription is extended by one month'));
            return response()->json([
                'success' => true
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'error_msg' => __('Something went wrong')
            ], 200);
        }
    }

    /**
     * Create New Plan
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createPlan(Request $request) 
    {
        $message = __('Your plan created successfully.');
        if (empty($request->selected_plan)) {
            return response()->json([
                'errors' => [
                    'plan' => [__('Please select at least one plan')],
                ],
            ], 422);
        }
        $selectedPlan = Plan::findOrFail($request->selected_plan);
        $response = Auth::user()->createStripeCustomerIfNull();
        if ($response['res_status'] == 'error') {
            return response()->json([
                'errors' => [
                    'message' => $response['message'],
                    'plan' => [$response['message']],
                ],
            ], 422);
        }

        $historyCount = UserPlanChange::query()->where(['user_id' => Auth::id()])->where('created_at', '>=', Carbon::now()->subHours(24))->count();
        if ($historyCount >= 3) {
            return response()->json([
                'errors' => [
                    'plan' => [__('You already created your plan 3 times in last 24 hours. Please try later.')],
                ],
            ], 422);
        }

        $activeCard = Auth::user()->activeStripeCard;
        if (!$activeCard) {
            return response()->json([
                'errors' => [
                    'message' => [__('Card is required.')],
                ],
            ], 422);
        }

        $user = Auth::user();
        if($selectedPlan->slug == 'free') {
            $welcomeCharges = StripeConnect::getCharges($activeCard->stripe_id, $user->stripe_customer_id, 100, 'Essaie gratuit');
            if ($welcomeCharges['res_status'] == 'error') {
                return response()->json([
                    'errors' => [
                        'message' => $welcomeCharges['message'],
                        'plan' => [$welcomeCharges['message']],
                    ],
                ], 422);
            }

            if (isset($welcomeCharges['res_status']) && $welcomeCharges['res_status'] == 'success') {
                $stripePaymentIntentId = $welcomeCharges['id'];
                $redirect_url = route('plan.payment.confirmation', [$selectedPlan->id, $user->id, $stripePaymentIntentId]);
                $confirmPaymentIntent = StripeConnect::confirmPaymentIntent($stripePaymentIntentId, $activeCard->stripe_id, $redirect_url);
                $data['stripe_subscription_id'] = null;
                if(isset($confirmPaymentIntent['status']) && $confirmPaymentIntent['status'] == 'requires_action') {
                    return response()->json([
                        'success' => 'requires_action',
                        'redirect_to_url' => $confirmPaymentIntent->next_action->redirect_to_url->url
                    ], 200);
                }

                if ($confirmPaymentIntent['res_status'] == 'success' && isset($confirmPaymentIntent['status']) && $confirmPaymentIntent['status'] == 'succeeded') {
                    $user->assignFreePlan();
                } else {
                    return response()->json([
                        'errors' => [
                            'message' => __('Plan selection is failed'),
                            'plan' => [__('Plan selection is failed')],
                        ],
                    ], 422);
                }
            }
        } else {
            $data = [
                'user_id' => $user->id,
                'plan_id' => $selectedPlan->id,
                'status' => 'active',
            ];

            if ($selectedPlan->id != Plan::TWO_YEAR_PLAN_ID) {
                $stripeSubscription = StripeConnect::createSubscription($user->stripe_customer_id, $selectedPlan->stripe_price_id);
                if ($stripeSubscription['res_status'] == 'error') {
                    return response()->json([
                        'errors' => [
                            'message' => $stripeSubscription['message'],
                            'plan' => [$stripeSubscription['message']],
                        ],
                    ], 422);
                }

                $stripeSubscription['plan_id'] = $selectedPlan->id;
                $subscription = StripeSubscription::createSubscription(Auth::id(), $stripeSubscription);
                $data['stripe_subscription_id'] = $subscription->id;
                $data['stripe_status'] = $stripeSubscription['status'];
                $data['expiration'] = Carbon::createFromTimestamp($stripeSubscription->current_period_end)->format('Y-m-d H:i:s');
            } else {
                $amount = $selectedPlan->price * 100;
                $planAmount = round($amount, 2);
                $getCharge = StripeConnect::getCharges($activeCard->stripe_id, $user->stripe_customer_id, $planAmount, 'Two Year Subscription Created');
                if ($getCharge['res_status'] == 'error') {
                    return response()->json([
                        'errors' => [
                            'message' => $getCharge['message'],
                            'plan' => [$getCharge['message']],
                        ],
                    ], 422);
                }

                if (isset($getCharge['res_status']) && $getCharge['res_status'] == 'success') {
                    $stripePaymentIntentId = $getCharge['id'];
                    $redirect_url = route('plan.payment.confirmation', [$selectedPlan->id, $user->id, $stripePaymentIntentId]);
                    $confirmPaymentIntent = StripeConnect::confirmPaymentIntent($stripePaymentIntentId, $activeCard->stripe_id, $redirect_url);
                    if ($confirmPaymentIntent['res_status'] == 'error') {
                        return response()->json([
                            'errors' => [
                                'message' => $confirmPaymentIntent['message'],
                                'plan' => [$confirmPaymentIntent['message']],
                            ],
                        ], 422);
                    }

                    $afterTwoYears = Carbon::now()->addYears(2)->format('Y-m-d H:i:s');
                    $data['stripe_subscription_id'] = null;
                    if(isset($confirmPaymentIntent['status']) && $confirmPaymentIntent['status'] == 'requires_action') {
                        $data['expiration'] = $afterTwoYears;
                        $data['stripe_payment_intent_id'] = $stripePaymentIntentId;
                        $data['stripe_status'] = 'incomplete';
                        $data['status'] = 'expired';
                        $data['plan_id'] = $selectedPlan->id;
                        UserPlan::create($data);

                        return response()->json([
                            'success' => 'requires_action',
                            'redirect_to_url' => $confirmPaymentIntent->next_action->redirect_to_url->url
                        ], 200);
                    }

                    if ($confirmPaymentIntent['res_status'] == 'success' && isset($confirmPaymentIntent['status']) && $confirmPaymentIntent['status'] == 'succeeded') {
                        $data['stripe_status'] = 'active';
                        $data['expiration'] = Carbon::now()->addYears(2)->format('Y-m-d H:i:s');
                    } else {
                        return response()->json([
                            'errors' => [
                                'message' => __('Plan selection is failed'),
                                'plan' => [__('Plan selection is failed')],
                            ],
                        ], 422);
                    }
                }
            }

            $userPlan = UserPlan::create($data);
            if($userPlan->status == 'active') {
                UserPlanChange::create([
                    'user_id' => Auth::id(),
                    'plan_id' => $selectedPlan->id,
                    'expiration' => $userPlan->expiration,
                ]);
            }
        }
        Session::flash('success', $message);
        return response()->json([
            'success' => true,
            'message' => $message,
        ], 200);
    }
}