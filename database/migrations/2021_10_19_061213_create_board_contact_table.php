<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoardContactTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('board_contact', function (Blueprint $table) {
            $table->id();
	        $table->bigInteger('board_id')->unsigned()->index();
	        $table->foreign('board_id')->references('id')->on('boards')->onDelete('cascade');
	        $table->bigInteger('contact_id')->unsigned()->index();
	        $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade');
	        $table->tinyInteger('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('board_contact');
    }
}
