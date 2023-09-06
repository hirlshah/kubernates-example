<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->index();
            $table->integer('is_email')->nullable()->default(0);
            $table->integer('is_sms')->nullable()->default(0);
            $table->integer('is_push_notifications')->nullable()->default(0);
            $table->integer('is_message')->nullable()->default(0);
            $table->integer('is_leads')->nullable()->default(0);
            $table->integer('is_event')->nullable()->default(0);
            $table->integer('is_notification')->nullable()->default(0);
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
        Schema::dropIfExists('notifications');
    }
}
