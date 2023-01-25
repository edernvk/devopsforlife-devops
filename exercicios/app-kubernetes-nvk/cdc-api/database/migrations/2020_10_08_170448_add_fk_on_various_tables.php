<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFkOnVariousTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        /* INFO: When using MySQL, InnoDB auto create an index for foreign key constraint */
        /*  That is why we must remove the FK constraint and index on `down()` of this file */
        /*  On `down()`, the column rename is placed in another Schema to avoid command naming conflicts */

        /**
         * - users -> cities
         * - cities -> state
         * - users -> teams
         * - healthdocs -> users
         * - messages_users -> users
         * - messages_users -> messages
         * - role_user -> users
         * - role_user -> roles
         */

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('city_id')->change();
            $table->foreign('city_id')
                ->references('id')
                ->on('cities');
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->unsignedBigInteger('state_id')->change();
            $table->foreign('state_id')
                ->references('id')
                ->on('states');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('team_id')->change();
            $table->foreign('team_id')
                ->references('id')
                ->on('teams');
        });

        Schema::table('healthdocs', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->change();
            $table->foreign('user_id')
                ->references('id')
                ->on('users');
        });

        Schema::table('messages_users', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->change();
            $table->foreign('user_id')
                ->references('id')
                ->on('users');
        });

        Schema::table('messages_users', function (Blueprint $table) {
            $table->unsignedBigInteger('message_id')->change();
            $table->foreign('message_id')
                ->references('id')
                ->on('messages');
        });

        Schema::table('role_user', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->change();
            $table->foreign('user_id')
                ->references('id')
                ->on('users');
        });

        Schema::table('role_user', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->change();
            $table->foreign('role_id')
                ->references('id')
                ->on('roles');
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->unsignedBigInteger('status_id')->change();
            $table->foreign('status_id')
                ->references('id')
                ->on('status_message');
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
            $table->dropForeign(['city_id']);
            $table->dropIndex('users_city_id_foreign');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('city_id')->change();
        });


        Schema::table('cities', function (Blueprint $table) {
            $table->dropForeign(['state_id']);
            $table->dropIndex('cities_state_id_foreign');
        });
        Schema::table('cities', function (Blueprint $table) {
            $table->bigInteger('state_id')->change();
        });


        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
            $table->dropIndex('users_team_id_foreign');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('team_id')->change();
        });


        Schema::table('healthdocs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropIndex('healthdocs_user_id_foreign');
        });
        Schema::table('healthdocs', function (Blueprint $table) {
            $table->bigInteger('user_id')->change();
        });


        Schema::table('messages_users', function (Blueprint $table) {
            $table->dropForeign(['user_id', 'message_id']);
            $table->dropIndex('messages_users_user_id_foreign');
            $table->dropIndex('messages_users_message_id_foreign');
        });
        Schema::table('messages_users', function (Blueprint $table) {
            $table->bigInteger('user_id')->change();
            $table->bigInteger('message_id')->change();
        });


        Schema::table('role_user', function (Blueprint $table) {
            $table->dropForeign(['user_id', 'role_id']);
            $table->dropIndex('role_user_user_id_foreign');
            $table->dropIndex('role_user_role_id_foreign');
        });
        Schema::table('role_user', function (Blueprint $table) {
            $table->bigInteger('user_id')->change();
            $table->bigInteger('role_id')->change();
        });


        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['status_id']);
            $table->dropIndex('messages_status_id_foreign');
        });
        Schema::table('messages', function (Blueprint $table) {
            $table->bigInteger('status_id')->change();
        });
    }
}
