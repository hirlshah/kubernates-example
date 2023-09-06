<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveColorToTrelloTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trello_tasks', function (Blueprint $table) {
            $table->dropColumn('color');
            $table->dropColumn('label');
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
            $table->string('color')->nullable()->after('order');
            $table->string('label')->nullable()->default(null)->after('color');
        });
    }
}
