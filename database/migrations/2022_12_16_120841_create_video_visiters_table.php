<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoVisitersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_visiters', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('prospection_videos_id')->unsigned()->index();
	        $table->foreign('prospection_videos_id')->references('id')->on('prospection_videos')->onDelete('cascade');
            $table->bigInteger('user_id')->nullable()->unsigned()->index();
	        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
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
        Schema::dropIfExists('video_visiters');
    }
}
