<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stripe_coupons', function (Blueprint $table) {
            $table->id();
            $table->string('stripe_id')->nullable()->default(NULL);
            $table->string('code', 255);
            $table->unsignedDecimal('percent_off',5,2)->nullable()->default(NULL);
            $table->unsignedDecimal('amount_off', 10, 2)->nullable()->default(NULL);
            $table->string('duration', 20)->nullable()->default(NULL);
            $table->unsignedInteger('duration_in_months')->nullable()->default(NULL);
            $table->datetime('expiration');
            $table->boolean('valid');
            $table->text('stripe_object')->nullable()->default(NULL);
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
        Schema::dropIfExists('stripe_coupons');
    }
}

