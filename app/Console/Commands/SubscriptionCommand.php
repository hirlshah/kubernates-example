<?php

namespace App\Console\Commands;

use App\Classes\Helper\StripeConnect;
use App\Models\Plan;
use App\Models\UserPlan;
use App\Models\UserPlanChange;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SubscriptionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:scan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan Subscriptions';

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
        foreach($connections as $connection) {
            $expireCheck = Carbon::now();
            $incompleteCheck = Carbon::now()->subDays(1);
            $userPlans = UserPlan::on($connection)
                ->where('status', 'active')
                ->whereNotNull('stripe_subscription_id')
                ->where('plan_id', '!=', Plan::FREE_PLAN_ID)
                ->where('plan_id', '!=', Plan::TWO_YEAR_PLAN_ID)
                ->where(function ($query) use ($expireCheck, $incompleteCheck){
                     $query->where('expiration', '<', $expireCheck);
                     $query->orWhere(function ($q) use ($incompleteCheck){
                         $q->where('stripe_status', 'incomplete');
                         $q->where('updated_at', '<', $incompleteCheck);
                     });
                 })
                 ->get();
            foreach($userPlans as $user_plan){
                $stripeSubscriptionModel = $user_plan->stripeSubscription;
                $subscriptionId = $stripeSubscriptionModel->stripe_id;
                $subscription = StripeConnect::getSubscription($subscriptionId);
                if(isset($subscription['id']) && isset($subscription['status']) && !empty($subscription['current_period_end'])){
                    if(in_array($subscription['status'], ['trialing', 'active'])){
                        $periodStart = Carbon::createFromTimestamp($subscription['current_period_start']);
                        $periodEnd = Carbon::createFromTimestamp($subscription['current_period_end']);
                        $user_plan->expiration = $periodEnd;
                        $user_plan->stripe_status = $subscription['status'];
                        $user_plan->save();

                        $stripeSubscriptionModel->period_start = $periodStart;
                        $stripeSubscriptionModel->period_end = $periodEnd;
                        $stripeSubscriptionModel->status = $subscription['status'];
                        $stripeSubscriptionModel->default_payment_method = $subscription['default_payment_method'];
                        $stripeSubscriptionModel->save();

                        UserPlanChange::on($connection)
                            ->create([
                                'user_id' => $user_plan->user_id,
                                'plan_id' => $user_plan->plan_id,
                                'expiration' => $user_plan->expiration,
                            ]);
                    } else {
                        $user_plan->status = 'expired';
                        $user_plan->stripe_status = $subscription['status'];
                        $user_plan->save();

                        $stripeSubscriptionModel->status = $subscription['status'];
                        $stripeSubscriptionModel->save();

                        echo "Subscription Failed:" . $subscription['id'];
                        //TODO:: Implement necessary steps for failed subscription
                    }
                }

            }

            return Command::SUCCESS;
        } 
    }
}