<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\AdminCoupon;
use Carbon\Carbon;

class SeedDefaultAdminPlans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $date = Carbon::now();
        $data = [
            ['code' => 'RU071', 'free_trial_days' => 7, 'valid_until' => NULL, 'is_active'=>1, 'created_at' => $date],
            ['code' => 'RU411', 'free_trial_days' => 14, 'valid_until' => NULL, 'is_active'=>1, 'created_at' => $date],
            ['code' => 'RU031', 'free_trial_days' => 30, 'valid_until' => NULL, 'is_active'=>1, 'created_at' => $date],
        ];
        AdminCoupon::query()->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        AdminCoupon::query()->truncate();
    }
}
