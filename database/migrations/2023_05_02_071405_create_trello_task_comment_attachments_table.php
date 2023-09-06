<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrelloTaskCommentAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trello_task_comment_attachments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('trello_task_comment_id')->nullable()->unsigned()->index();
            $table->foreign('trello_task_comment_id')->references('id')->on('trello_task_comments')->onDelete('cascade');
            $table->string('type')->nullable();
            $table->string('name')->nullable();
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
        Schema::dropIfExists('trello_task_comment_attachments');
        Schema::enableForeignKeyConstraints();
    }
}
