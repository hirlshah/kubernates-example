<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code', 25);
            $table->unsignedMediumInteger('free_trial_days')->default(0);
            $table->dateTime('valid_until')->nullable()->default(NULL);
            $table->boolean('is_active')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_coupons');
    }
}
