<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAndRenameFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trello_statuses', function (Blueprint $table) {
            $table->bigInteger('trello_board_id')->nullable()->unsigned()->index()->after('user_id');
            $table->foreign('trello_board_id')->references('id')->on('trello_boards')->onDelete('cascade');
        });

        Schema::table('trello_tasks', function (Blueprint $table) {
            $table->bigInteger('trello_board_id')->nullable()->unsigned()->index()->after('user_id');
            $table->foreign('trello_board_id')->references('id')->on('trello_boards')->onDelete('cascade');
            $table->renameColumn('reminder_date', 'deadline_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trello_statuses', function (Blueprint $table) {
            $table->dropColumn('trello_board_id');
        });

        Schema::table('trello_tasks', function (Blueprint $table) {
            $table->dropColumn('trello_board_id');
            $table->renameColumn('deadline_date', 'reminder_date');
        });
    }
}
