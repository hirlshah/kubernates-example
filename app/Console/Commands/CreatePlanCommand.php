<?php

namespace App\Console\Commands;

use App\Classes\Helper\StripeConnect;
use App\Models\Plan;
use App\Models\StripePrice;
use App\Models\StripeProduct;
use Illuminate\Console\Command;

class CreatePlanCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plan:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create product, prices in stripe';

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
            $plans = Plan::on($connection)->where('is_active', 1)->get();
            foreach($plans as $plan){
                try{
                    if($plan->price == 0) {
                        continue;
                    }
                    if($plan->stripe_price_id){
                        $stripePlan = StripeConnect::getPrice($plan->stripe_price_id);
                        if(empty($stripePlan['id'])){
                            $this->createPrice($plan);
                        }
                    } else {
                        $this->createPrice($plan);
                    }
                } catch (\Exception $exception){
                    $this->error($exception->getMessage());
                    return Command::FAILURE;
                }

            }
            return Command::SUCCESS;
        }
    }

    public function createPrice($plan){
        $connections = dbConnections();
        foreach($connections as $connection) {
            $productId = 'rankup_product_' . $plan->slug;
            $product = StripeConnect::getProduct($productId);
            if(empty($product['id'])){
                $product = StripeConnect::createProduct($plan->slug, $plan->slug, [], $productId);
                if(empty($product['id'])){
                    throw new \Exception("Unable to create " . $productId);
                }
            }

            $dbProduct = StripeProduct::on($connection)
                ->updateOrCreate(
                    ['stripe_id' => $product['id']],
                    ['name' => $product['name'], 'is_active'=> $product['active'], 'stripe_object' => json_encode($product)]
                );

            $loopupKey = 'rankup_price_'. $plan->slug;
            $prices = StripeConnect::searchPrice($loopupKey);
            if(empty($prices['data']) || empty($prices['data'][0])){
                $price = StripeConnect::createPrice('USD', $productId, ($plan->price * 100), [], $loopupKey, $plan->interval, $plan->interval_count, $plan->auto_renew);

                if(empty($price['id'])){
                    throw new \Exception("Unable to create " . $loopupKey);
                }
            } else {
                $price = $prices['data'][0];
            }
    
            $dbPrice = StripePrice::on($connection)
                ->updateOrCreate(
                    ['stripe_id' => $price['id']],
                    [
                        'stripe_product_id' => $dbProduct->id,
                        'unit_amount_decimal' => $price['unit_amount'],
                        'recurring_type' => !empty($price['recurring']) ? $price['recurring']['interval'] : 'one_time',
                        'is_active' => $price['active'],
                        'stripe_object' => json_encode($price)
                    ]
                );
            $plan->stripe_price_id = $price['id'];
            $plan->save();

            return $dbPrice;
        }
    }
}