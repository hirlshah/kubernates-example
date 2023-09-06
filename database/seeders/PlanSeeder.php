<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\UserPlan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Plan::insert([
            ['id' => Plan::FREE_PLAN_ID,'slug' => 'free', 'price' => 0, 'currency' => 'USD', 'interval'=>'week', 'is_active'=>1],
            ['id' => 2, 'slug' => 'standard', 'price' => 14, 'currency' => 'USD', 'interval'=>'month', 'interval_count' => 1, 'is_active'=>0],
            ['id' => 3,'slug' => 'pro_month', 'price' => 27, 'currency' => 'USD', 'interval'=>'month', 'interval_count' => 1, 'is_active'=>1],
            ['id' => 4,'slug' => 'pro_year', 'price' => 274, 'currency' => 'USD', 'interval'=>'year', 'interval_count' => 1,'is_active'=>1],
            ['id' => 5,'slug' => 'pro_year_199', 'price' => 199, 'currency' => 'USD', 'interval'=>'year', 'interval_count' => 1, 'is_active'=>1],
            ['id' => 6,'slug' => 'pro_bi_year', 'price' => 299, 'currency' => 'USD', 'interval'=>'year', 'interval_count' => 2, 'is_active'=>1, 'auto_renew' => 0],
        ]);
    }
}
