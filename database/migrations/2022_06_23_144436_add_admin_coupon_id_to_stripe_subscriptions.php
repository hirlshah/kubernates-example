<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdminCouponIdToStripeSubscriptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stripe_subscriptions', function (Blueprint $table) {
            $table->unsignedBigInteger('admin_coupon_id')->nullable()->default(NULL)->after('stripe_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stripe_subscriptions', function (Blueprint $table) {
            $table->dropColumn('admin_coupon_id');
        });
    }
}
