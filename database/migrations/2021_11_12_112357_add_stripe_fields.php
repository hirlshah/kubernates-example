<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStripeFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('stripe_customer_id')->nullable();
            $table->datetime('subscription_ends_at')->nullable();
        });

        Schema::create('stripe_cards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('stripe_id')->nullable();
            $table->string('card_holder_name')->nullable();
            $table->string('card_last_four')->nullable();
            $table->string('card_brand')->nullable();
            $table->string('card_expiry_date')->nullable();
            $table->tinyInteger('is_active')->default(0);
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('stripe_customer_id');
            $table->dropColumn('subscription_ends_at');
        });
        Schema::dropIfExists('stripe_cards');
    }
}
