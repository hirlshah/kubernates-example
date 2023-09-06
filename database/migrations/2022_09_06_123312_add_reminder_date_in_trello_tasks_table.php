<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReminderDateInTrelloTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trello_tasks', function (Blueprint $table) {
            $table->timestamp('reminder_date')->nullable()->default(null)->after('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trello_tasks', function (Blueprint $table) {
            $table->dropColumn('reminder_date');
        });
    }
}
