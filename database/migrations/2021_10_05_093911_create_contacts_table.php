<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
	        $table->id();
	        $table->bigInteger('user_id')->unsigned()->index();
	        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('event_id')->unsigned()->index()->nullable();
	        $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
	        $table->string('name');
	        $table->string('email')->nullable();
	        $table->string('phone')->nullable();
	        $table->string('profile_image')->nullable();
	        $table->string('contacted_through')->nullable();
	        $table->text('message')->nullable();
            $table->unsignedMediumInteger('order')->default(0);
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
        Schema::dropIfExists('contacts');
    }
}
