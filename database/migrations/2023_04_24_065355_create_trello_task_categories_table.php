<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrelloTaskCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trello_task_categories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('trello_task_id')->nullable()->unsigned()->index();
            $table->foreign('trello_task_id')->references('id')->on('trello_tasks')->onDelete('cascade');
            $table->bigInteger('trello_board_category_id')->nullable()->unsigned()->index();
            $table->foreign('trello_board_category_id')->references('id')->on('trello_board_categories')->onDelete('cascade');
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
        Schema::dropIfExists('trello_task_categories');
        Schema::enableForeignKeyConstraints();
    }
}
