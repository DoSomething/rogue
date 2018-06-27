<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSourceDetailsColumnToSignupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('signups', function (Blueprint $table) {
            $table->string('source_details')->after('source')->nullable()->comment('Extra details about the signup source, like referral source from url query string');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('signups', function (Blueprint $table) {
            $table->dropColumn('source_details');
        });
    }
}
