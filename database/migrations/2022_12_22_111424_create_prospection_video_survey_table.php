<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProspectionVideoSurveyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prospection_video_survey', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('prospection_video_id')->unsigned()->index()->nullable();
	        $table->foreign('prospection_video_id')->references('id')->on('prospection_videos')->onDelete('cascade');
            $table->unsignedBigInteger('survey_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('contact_id')->nullable();
            $table->unsignedBigInteger('question_id')->nullable();
            $table->string('answer_ids')->nullable();
            $table->text('answer_text')->nullable();
            $table->text('comment')->nullable();
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
        Schema::dropIfExists('prospection_video_survey');
    }
}
