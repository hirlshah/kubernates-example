<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Classes\Helper\StripeConnect;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Plan;
use App\Models\UserPlan;
use App\Models\UserPlanChange;
use Session;
use MetaTag;


class FrontendController extends Controller
{
    /**
     * Home page
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function index(Request $request) 
    {
        MetaTag::set('title', config('app.rankup.company_title')." - ".__('Home'));
        MetaTag::set('description', config('app.rankup.company_title').' Home Page');
        MetaTag::set('image', asset(config('app.rankup.company_logo_path')));
        if(Auth::check()) {
             if(Auth::user()->hasRole('Seller')) {
                return redirect()->route('seller-dashboard');
             }
             return redirect()->route('admin-dashboard');
        } else if(!Auth::check() && isset($request->ref) && !empty($request->ref)){
	        session(['referral_code' => $request->ref]);
	        return redirect()->route('register');
        }
	    return redirect()->to('http://www.rankup.io');
    }

    /**
     * Plans page
     *
     * @return Response
     */
    public function plans() 
    {
        MetaTag::set('title', config('app.rankup.company_title').' - Plans');
        MetaTag::set('description', config('app.rankup.company_title').' Plans Page');
        MetaTag::set('image', asset(config('app.rankup.company_logo_path')));
        return view('frontend.plans');
    }

    /**
     * Plan Payment Confirmation.
     *
     * @param $planId
     * @param $userId
     * @param $stripePaymentIntentId
     *
     * @return Response|Application|RedirectResponse|Redirector|void
     */
    public function planPaymentConfirmation($planId, $userId, $stripePaymentIntentId) 
    {
        $paymentIntent = StripeConnect::getPaymentIntent($stripePaymentIntentId);
        if (isset($paymentIntent) && $paymentIntent['status'] == 'succeeded') {
            $myPlan = UserPlan::where(['plan_id' => $planId, 'user_id' => $userId])->first();
            $message = __('Plan selected successfully');
            $messageType = 'success';
            if($planId == Plan::FREE_PLAN_ID) {
                $message = __('Your plan created successfully.');
                $user = User::find($userId);
                $user->assignFreePlan();
            }elseif(isset($myPlan) && !empty($myPlan)) {
                $myPlan->stripe_status = 'active';
                $myPlan->status = 'active';
                $myPlan->save();
                if ($myPlan->status == 'active') {
                    UserPlanChange::create([
                        'user_id' => $userId,
                        'plan_id' => $myPlan->id,
                        'expiration' => $myPlan->expiration,
                    ]);
                }
            }
        } else {
            $message = __('Plan selection is failed');
            $messageType = 'error';
        }

        Session::flash($messageType, $message);
        return redirect(route('seller.setting.my-subscription'));
    }
}