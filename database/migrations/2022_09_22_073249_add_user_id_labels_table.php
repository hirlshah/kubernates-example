<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdLabelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('labels', function (Blueprint $table) {
	        $table->bigInteger('user_id')->unsigned()->after('id')->index()->nullable();
	        $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('labels', function (Blueprint $table) {
	        Schema::disableForeignKeyConstraints();
	        $table->dropForeign(['user_id']);
	        $table->dropColumn('user_id');
	        Schema::enableForeignKeyConstraints();
        });
    }
}
