<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomTitleToProspectionVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prospection_videos', function (Blueprint $table) {
            $table->string('custom_title')->nullable()->default(NULL)->after('title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prospection_videos', function (Blueprint $table) {
            $table->dropColumn('custom_title');
        });
    }
}
