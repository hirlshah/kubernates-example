<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
	        $table->string('user_name')->unique()->nullable()->default(NULL);
            $table->string('name');
            $table->string('email')->unique();
	        $table->integer('phone')->nullable()->default(NULL);
            $table->timestamp('email_verified_at')->nullable()->default(NULL);
            $table->string('password')->nullable()->default(NULL);
            $table->string('permanent_zoom_link', 1000)->nullable()->default(NULL);
	        $table->string('date_of_birth')->nullable()->default(NULL);
            $table->string('city')->nullable()->default(NULL);
            $table->enum('gender',['Male','Female'])->nullable()->default(NULL);
            $table->integer('age')->nullable()->default(null);
            $table->string('country')->nullable()->default(NULL);
	        $table->string('description')->nullable()->default(NULL);
	        $table->string('video')->nullable()->default(NULL);
            $table->string('referral_code')->nullable()->default(NULL);
            $table->string('profile_image')->nullable()->default(NULL);
            $table->enum('tree_pos', ['left', 'right'])->default(NULL);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
