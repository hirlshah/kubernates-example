<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrelloTaskAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trello_task_attachments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('trello_task_id')->nullable()->unsigned()->index();
            $table->foreign('trello_task_id')->references('id')->on('trello_tasks')->onDelete('cascade');
            $table->string('attachment')->nullable();
            $table->string('type')->nullable();
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
        Schema::dropIfExists('trello_task_attachments');
        Schema::enableForeignKeyConstraints();
    }
}
