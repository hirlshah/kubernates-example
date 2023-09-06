<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\UserPlan;
use App\Models\UserPlanChange;
use App\Classes\Helper\StripeConnect;
use Illuminate\Support\Facades\Log;
use App\Models\Plan;

class TwoYearSubscriptionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'twoYearSubscription:scan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update two year subscription';

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
            try {
                $today = Carbon::now()->subDay()->format('Y-m-d H:i:s');
                $afterTwoYears = Carbon::now()->addYears(2)->format('Y-m-d H:i:s');

                $userPlans = UserPlan::on($connection)
                    ->where('status', 'active')
                    ->where('plan_id', Plan::TWO_YEAR_PLAN_ID)
                    ->whereDate('expiration', $today)
                    ->get();

                foreach($userPlans as $userPlan) {
                    $plan = $userPlan->plan;
                    $amount = $plan->price * 100;
                    $planAmount = round($amount, 2);
                    $user = $userPlan->user;
                    $activeCard = $user->activeStripeCard;
                    $userPlan->status = 'expired';
                    $userPlan->stripe_status = 'canceled';

                    if ($activeCard) {
                        $getCharge = StripeConnect::getCharges($activeCard->stripe_id, $user->stripe_customer_id, $planAmount, 'Two year subscription');

                        if ($getCharge['res_status'] == 'success') {
                            $userPlan->expiration = $afterTwoYears;
                            $userPlan->status = 'active';
                            $userPlan->stripe_status = 'active';
                        }
                    }
                    $userPlan->save();
                    if($userPlan->status == 'active') {
                        UserPlanChange::on($connection)
                            ->create([
                                'user_id' => $user->id,
                                'plan_id' => $userPlan->plan_id,
                                'expiration' => $userPlan->expiration,
                            ]);
                        Log::info('Two year plan updated successfully '. $userPlan->id);
                    } else {
                        Log::info('Two year plan expired '. $userPlan->id);
                    }
                }
            } catch (\Exception $e) {
                Log::warning($e->getMessage());
            }
        }
    }
}
