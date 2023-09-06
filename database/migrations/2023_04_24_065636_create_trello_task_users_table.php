<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrelloTaskUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trello_task_users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('trello_task_id')->nullable()->unsigned()->index();
            $table->foreign('trello_task_id')->references('id')->on('trello_tasks')->onDelete('cascade');
            $table->bigInteger('user_id')->nullable()->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('trello_task_users');
        Schema::enableForeignKeyConstraints();
    }
}
