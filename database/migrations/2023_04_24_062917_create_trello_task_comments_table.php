<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrelloTaskCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trello_task_comments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('trello_task_id')->nullable()->unsigned()->index();
            $table->foreign('trello_task_id')->references('id')->on('trello_tasks')->onDelete('cascade');
            $table->bigInteger('parent_id')->unsigned()->index()->nullable();
            $table->foreign('parent_id')->references('id')->on('trello_task_comments')->onDelete('cascade');
            $table->bigInteger('user_id')->nullable()->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('message')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
        Schema::dropIfExists('trello_task_comments');
        Schema::enableForeignKeyConstraints();
    }
}
