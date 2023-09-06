<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLastNameVideoVisitersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('video_visiters', function (Blueprint $table) {
            $table->string('last_name')->nullable()->default(NULL)->after('phone');
            $table->string('email')->nullable()->default(NULL)->after('last_name');
            $table->renameColumn('name', 'first_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('video_visiters', function (Blueprint $table) {
            $table->dropColumn('last_name');
            $table->dropColumn('email');
            $table->renameColumn('first_name', 'name');
        });
    }
}
