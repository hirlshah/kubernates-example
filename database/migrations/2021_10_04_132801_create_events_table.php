<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
	        $table->string('name');
            $table->string('slug')->unique();
	        $table->date('meeting_date')->nullable();
	        $table->time('meeting_time')->nullable();
            $table->text('content')->nullable();
            $table->string('image')->nullable();
            $table->string('meeting_url')->nullable();
            $table->bigInteger('user_id')->unsigned()->index();
            $table->unsignedBigInteger('survey_id')->nullable()->default(NULL);
	        $table->tinyInteger('is_active')->default(1);
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
        Schema::dropIfExists('events');
    }
}
