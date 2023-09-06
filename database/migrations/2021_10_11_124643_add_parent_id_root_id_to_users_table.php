<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParentIdRootIdToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->after('id')->nullable()->default(NULL);
            $table->unsignedBigInteger('root_id')->after('parent_id')->nullable()->default(NULL);

            $table->foreign('parent_id', 'users_for_parent_id')->references('id')->on('users');
            $table->foreign('root_id', 'users_for_root_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['parent_id', 'root_id']);
            $table->dropForeign(['users_for_parent_id', 'users_for_root_id']);
        });
    }
}
