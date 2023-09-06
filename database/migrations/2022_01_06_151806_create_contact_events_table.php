<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_events', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('event_id')->unsigned()->index();
	        $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->bigInteger('contact_id')->unsigned()->index();
	        $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade');
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
        Schema::dropIfExists('contact_events');
    }
}
