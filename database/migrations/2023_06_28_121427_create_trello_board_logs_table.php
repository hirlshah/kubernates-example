<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrelloBoardLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trello_board_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable()->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('trello_task_id')->nullable()->unsigned()->index();
            $table->foreign('trello_task_id')->references('id')->on('trello_tasks')->onDelete('cascade');
            $table->bigInteger('status')->nullable()->unsigned()->index();
            $table->foreign('status')->references('id')->on('trello_statuses')->onDelete('cascade');
            $table->bigInteger('previous_status')->nullable()->unsigned()->index();
            $table->foreign('previous_status')->references('id')->on('trello_statuses')->onDelete('cascade');
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
        Schema::dropIfExists('trello_board_logs');
        Schema::enableForeignKeyConstraints();
    }
}
