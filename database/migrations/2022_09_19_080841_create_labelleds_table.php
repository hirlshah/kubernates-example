<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLabelledsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('labelleds', function (Blueprint $table) {
            $table->id();
	        $table->bigInteger('label_id')->unsigned()->index();
	        $table->foreign('label_id')->references('id')->on('labels')->onDelete('cascade');
	        $table->morphs('labelled');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('labelleds');
    }
}
