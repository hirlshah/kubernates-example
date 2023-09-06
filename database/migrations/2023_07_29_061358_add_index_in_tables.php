<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexInTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->index(['created_at']);
        });

        Schema::table('board_contact', function (Blueprint $table) {
            $table->index(['status']);
            $table->index(['updated_at']);
        });

        Schema::table('contact_logs', function (Blueprint $table) {
            $table->index(['created_at']);
        });

        Schema::table('event_reps', function (Blueprint $table) {
            $table->index(['event_id']);
            $table->index(['member_id']);
            $table->index(['created_at']);
            $table->index(['status']);
        });

        Schema::table('user_plan_changes', function (Blueprint $table) {
            $table->index(['plan_id']);
        });

        Schema::table('video_visiters', function (Blueprint $table) {
            $table->index(['created_at']);
            $table->index(['start_date']);
            $table->index(['end_date']);
        });

        Schema::table('follow_ups', function (Blueprint $table) {
            $table->index(['user_id']);
            $table->index(['follow_up_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });

        Schema::table('board_contact', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['updated_at']);
        });

        Schema::table('contact_logs', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });

        Schema::table('event_reps', function (Blueprint $table) {
            $table->dropIndex(['event_id']);
            $table->dropIndex(['member_id']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['status']);
        });

        Schema::table('user_plan_changes', function (Blueprint $table) {
            $table->dropIndex(['plan_id']);
        });

        Schema::table('video_visiters', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
            $table->dropIndex(['start_date']);
            $table->dropIndex(['end_date']);
        });

        Schema::table('follow_ups', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['follow_up_date']);
        });
    }
}