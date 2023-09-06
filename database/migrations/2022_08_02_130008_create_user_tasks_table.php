<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_tasks', function (Blueprint $table) {
            $table->id();
	        $table->bigInteger('user_id')->unsigned()->index();
	        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
	        $table->json('tasks')->nullable();
	        $table->boolean('is_complete')->default(0);
	        $table->timestamp('task_date');
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
        Schema::dropIfExists('user_tasks');
    }
}
