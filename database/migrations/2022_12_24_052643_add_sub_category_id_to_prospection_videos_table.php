<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubCategoryIdToProspectionVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prospection_videos', function (Blueprint $table) {
            $table->integer('sub_category_id')->nullable()->after('survey_id');
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
            $table->dropColumn('sub_category_id');
        });
    }
}
