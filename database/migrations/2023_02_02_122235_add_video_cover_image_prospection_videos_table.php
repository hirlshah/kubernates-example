<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVideoCoverImageProspectionVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prospection_videos', function (Blueprint $table) {
            $table->string('video_cover_image')->nullable()->default(NULL)->after('video');
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
            $table->dropColumn('video_cover_image');
        });
    }
}
