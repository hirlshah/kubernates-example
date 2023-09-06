<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubCategoryIdToVideosDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->integer('parent_id')->default(0)->after('name');
        });
        Schema::table('documents', function (Blueprint $table) {
            $table->integer('sub_category_id')->default(0)->after('category_id');
        });
        Schema::table('videos', function (Blueprint $table) {
            $table->integer('sub_category_id')->default(0)->after('category_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('parent_id');
        });
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('sub_category_id');
        });
        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn('sub_category_id');
        });
    }
}
