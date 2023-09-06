<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Plan;
use App\Models\UserPlan;

class ProPlanChanges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("ALTER TABLE `plans` CHANGE `monthly_price` `price` DECIMAL(10,2) NOT NULL; ");
        Schema::table('plans', function (Blueprint $table){
            $table->string('interval', 10)->after('price')->nullable()->default(NULL);
        });
        $free = Plan::where('slug', 'free')->first();
        $standard = Plan::where('slug', 'standard')->first();
        $pro = Plan::where('slug', 'pro')->first();
        if($free){
            $free->interval = 'week';
            $free->save();
        }
        if($standard){
            $standard->interval = 'month';
            $standard->save();
        }
        if($pro){
            $pro->slug = 'pro_month';
            $pro->interval = 'month';
            $pro->save();
            UserPlan::where('plan_id', $pro->id)->update(['status' => 'expired']);
            Plan::create([
                'slug'=>'pro_year',
                'price' => 274,
                'currency' => 'USD',
                'interval' => 'year'
            ]);
        }



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("ALTER TABLE `plans` CHANGE `price` `monthly_price` DECIMAL(10,2) NOT NULL; ");
        Schema::table('plans', function (Blueprint $table){
            $table->dropColumn(['interval']);
        });
        $pro = Plan::where('slug', 'pro_month')->first();
        if($pro){
            $pro->slug = 'pro';
            $pro->save();
            UserPlan::where('plan_id', $pro->id)->update(['status' => 'active']); // Expiration date check still exist
        }
        Plan::where('slug', 'pro_year')->forceDelete();
    }
}
