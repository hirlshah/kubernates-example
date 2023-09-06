<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameColumnProspectionVideosIdInVideoVisitersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('video_visiters', function (Blueprint $table) {
            $table->renameColumn('prospection_videos_id', 'prospection_video_id');
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
            $table->renameColumn('prospection_video_id', 'prospection_videos_id');
        });
    }
}
