<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->bigInteger('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('repeat_days')->nullable();
            $table->boolean('repeat_monday')->default(0);
            $table->boolean('repeat_tuesday')->default(0);
            $table->boolean('repeat_wednesday')->default(0);
            $table->boolean('repeat_thursday')->default(0);
            $table->boolean('repeat_friday')->default(0);
            $table->boolean('repeat_saturday')->default(0);
            $table->boolean('repeat_sunday')->default(0);
            $table->timestamps();
	        $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
