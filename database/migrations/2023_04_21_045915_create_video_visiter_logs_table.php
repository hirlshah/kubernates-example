<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoVisiterLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_visiter_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('prospection_video_id')->unsigned()->index();
	        $table->foreign('prospection_video_id')->references('id')->on('prospection_videos')->onDelete('cascade');
            $table->bigInteger('user_id')->nullable()->unsigned()->index();
	        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('contact_id')->nullable()->unsigned()->index();
            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade');
            $table->bigInteger('referral_user_id')->nullable()->unsigned()->index();
            $table->foreign('referral_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->string('first_name')->nullable()->default(NULL);
            $table->string('phone')->nullable()->default(NULL);
            $table->string('last_name')->nullable()->default(NULL);
            $table->string('email')->nullable()->default(NULL);
            $table->string('time')->default('0:00')->nullable();
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
        Schema::dropIfExists('video_visiter_logs');
    }
}
