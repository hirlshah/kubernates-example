<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReferralUserIdFieldToVideoVisitersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('video_visiters', function (Blueprint $table) {
            $table->bigInteger('referral_user_id')->nullable()->unsigned()->index()->after('contact_id');
            $table->foreign('referral_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('video_visiters', function (Blueprint $table) {
            Schema::disableForeignKeyConstraints();
            $table->dropForeign(['referral_user_id']);
            $table->dropColumn('referral_user_id');
            Schema::enableForeignKeyConstraints();
        });
    }
}
