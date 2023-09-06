<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserEventSurveyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_event_survey', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('survey_id');
            $table->unsignedBigInteger('user_id')->nullable()->default(NULL);
            $table->unsignedBigInteger('question_id');
            $table->string('answer_ids')->nullable()->default(NULL);
            $table->text('answer_text')->nullable()->default(NULL);
            $table->text('comment')->nullable()->default(NULL);
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
        Schema::dropIfExists('user_event_survey');
    }
}
