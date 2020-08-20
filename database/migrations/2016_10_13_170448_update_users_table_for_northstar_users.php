<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersTableForNorthstarUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('email');
            $table->dropColumn('password');
            $table->dropColumn('remember_token');
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });

        // Dropping the columns above and redefining them here was done to
        // reorder the columns.
        Schema::table('users', function (Blueprint $table) {
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
        Schema::table('users', function (Blueprint $table) {
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
        });
    }
}
