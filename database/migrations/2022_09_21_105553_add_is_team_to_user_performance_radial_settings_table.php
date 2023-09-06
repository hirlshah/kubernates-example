<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsTeamToUserPerformanceRadialSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_performance_radial_settings', function (Blueprint $table) {
            $table->tinyInteger('is_team')->default(0)->after('no_of_distributors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_performance_radial_settings', function (Blueprint $table) {
            $table->dropColumn('is_team');
        });
    }
}
