<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrelloTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trello_tasks', function (Blueprint $table) {
            $table->id();
	        $table->bigInteger('user_id')->unsigned()->index();
	        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
	        $table->bigInteger('trello_status_id')->unsigned()->index();
	        $table->foreign('trello_status_id')->references('id')->on('trello_statuses')->onDelete('cascade');
	        $table->string('title');
	        $table->text('description')->nullable();
	        $table->string('color')->nullable();
	        $table->unsignedMediumInteger('order')->default(0);
	        $table->softDeletes();
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
        Schema::dropIfExists('trello_tasks');
    }
}
