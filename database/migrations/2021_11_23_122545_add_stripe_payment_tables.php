<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStripePaymentTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stripe_products', function (Blueprint $table) {
            $table->id();
            $table->string('stripe_id')->nullable()->default(NULL);
            $table->string('name', 100);
            $table->string('description')->nullable()->default(NULL);
            $table->boolean('is_active')->default(0);
            $table->text('stripe_object')->nullable()->default(NULL);
            $table->timestamps();
        });

        Schema::create('stripe_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stripe_product_id')->index();
            $table->string('stripe_id')->nullable()->default(NULL);
            $table->unsignedInteger('unit_amount_decimal');
            $table->string('recurring_type', 50);
            $table->boolean('is_active')->default(0);
            $table->text('stripe_object')->nullable()->default(NULL);
            $table->timestamps();
        });

        Schema::create('stripe_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('plan_id')->references('id')->on('plans');
            $table->string('stripe_id')->nullable()->default(NULL);
            $table->string('item')->nullable()->default(NULL);
            $table->string('price')->nullable()->default(NULL);
            $table->string('product')->nullable()->default(NULL);
            $table->string('coupon_code')->nullable()->default(null);
            $table->string('default_payment_method', 45)->nullable()->default(NULL);
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('unit_amount_decimal');
            $table->unsignedInteger('total_amount_decimal');
            $table->string('currency', 5);
            $table->string('interval', 45);
            $table->dateTime('period_start');
            $table->dateTime('period_end')->nullable()->default(NULL);
            $table->dateTime('canceled_at')->nullable()->default(NULL);
            $table->string('status', 20)->default(NULL)->nullable();
            $table->longText('stripe_object')->nullable()->default(NULL);
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
        Schema::dropIfExists('stripe_products');
        Schema::dropIfExists('stripe_prices');
        Schema::dropIfExists('stripe_subscriptions');
    }
}
