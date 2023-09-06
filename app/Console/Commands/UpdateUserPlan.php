<?php

namespace App\Console\Commands;

use App\Classes\Helper\StripeConnect;
use App\Models\Plan;
use App\Models\StripeSubscription;
use App\Models\User;
use App\Models\UserPlan;
use App\Models\UserPlanChange;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateUserPlan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:userPlan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check for each user and if user\'s free plan is expired then upgrade user to standard plan';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $connections = dbConnections();
        foreach($connections as $connection){
            $today = Carbon::now()->format('Y-m-d');
            $userPlans = UserPlan::on($connection)->where(['status' => 'active', 'plan_id' => Plan::FREE_PLAN_ID])->whereDate('expiration', '<', $today)->get();

            foreach ($userPlans as $userPlan) {

                $selectedPlan = Plan::on($connection)->where(['is_active' => 1, 'slug' => 'pro_month'])->first();
                
                $user = User::on($connection)->find($userPlan->user_id);

                $myPlan = UserPlan::getUserPlan($user->id);
                $isPlanActive = false;
                if(empty($myPlan)) {
                    continue;
                }
                $isPlanActive = UserPlan::isPlanActive($myPlan);
                if($isPlanActive){
                    continue;
                }

                $response = $user->createStripeCustomerIfNull();

                if ($response['res_status'] == 'error') {
                    $userPlan->status = 'expired';
                    $userPlan->save();
                    Log::error($response['message']);
                    $this->error($response['message']);
                    continue;
                }

                $activeCard = $user->activeStripeCard;

                if (!$activeCard) {
                    $userPlan->status = 'expired';
                    $userPlan->last_error = "No active card found.";
                    $userPlan->save();

                    Log::error('Card is required.');
                    $this->error('Card is required.');
                    continue;
                }

                $getCardFromStripe = StripeConnect::getCard($user->stripe_customer_id, $activeCard->stripe_id);
                if(is_array($getCardFromStripe) && empty($getCardFromStripe['id'])){
                    $error = !empty($getCardFromStripe['message'])? $getCardFromStripe['message'] : "Card not found on stripe.";
                    $userPlan->last_error = $error;
                    $userPlan->status = 'expired';
                    $userPlan->save();

                    Log::error($error);
                    $this->error($error);
                    continue;
                }

                $stripeSubscription = StripeConnect::createSubscription($user->stripe_customer_id, $selectedPlan->stripe_price_id, null);
                if ($stripeSubscription['res_status'] == 'error') {
                    Log::error($stripeSubscription['message'] . '----' . $stripeSubscription['message']);
                    $this->error($stripeSubscription['message']);
                    continue;
                }
                if(in_array($stripeSubscription['status'], ['trialing', 'active', 'incomplete'])){
                    $stripeSubscription['promo'] = null;
                    $stripeSubscription['plan_id'] = $selectedPlan->id;
                    $subscription = StripeSubscription::createSubscription($user->id, $stripeSubscription);

                    $userPlan->plan_id = $selectedPlan->id;
                    $userPlan->stripe_subscription_id = $subscription->id;
                    $userPlan->stripe_status = $stripeSubscription['status'];
                    $userPlan->expiration = Carbon::createFromTimestamp($stripeSubscription->current_period_end)->format('Y-m-d H:i:s');
                    $userPlan->save();

                    UserPlanChange::on($connection)
                        ->create([
                            'user_id' => $user->id,
                            'plan_id' => $selectedPlan->id,
                            'expiration' => Carbon::createFromTimestamp($stripeSubscription->current_period_end)->format('Y-m-d H:i:s'),
                        ]);
                }
            }
            return true;
        }    
    }
}