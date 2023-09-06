<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeleteField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('documents', function(Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('events', function(Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('videos', function(Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('categories', function(Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('user_educations', function(Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('user_experiences', function(Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('contacts', function(Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('contact_events', function(Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('contact_logs', function(Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('board_contact', function(Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('follow_ups', function(Blueprint $table) {
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
        Schema::table('users', function(Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('documents', function(Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('events', function(Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('videos', function(Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('categories', function(Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('user_educations', function(Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('user_experiences', function(Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('contacts', function(Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('contact_events', function(Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('contact_logs', function(Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('board_contact', function(Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('follow_ups', function(Blueprint $table) {
            $table->dropSoftDeletes();
        });
        

    }
}
