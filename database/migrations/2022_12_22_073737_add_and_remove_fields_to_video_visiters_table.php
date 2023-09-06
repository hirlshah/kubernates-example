<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAndRemoveFieldsToVideoVisitersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('video_visiters', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('email');
            $table->bigInteger('contact_id')->nullable()->unsigned()->index()->after('user_id');
            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade');
            $table->dateTime('start_date')->nullable()->after('contact_id');
            $table->dateTime('end_date')->nullable()->after('start_date');
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
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->dropColumn('contact_id');
            $table->dropColumn('start_date');
            $table->dropColumn('end_date');
        });
    }
}
