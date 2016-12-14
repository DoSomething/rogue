<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultImageColumnToReportbackItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reportback_items', function (Blueprint $table) {
            $table->integer('default_image')->nullable()->after('remote_addr')->comment('Whether or not the image is a default and not from the user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reportback_items', function (Blueprint $table) {
            $table->dropColumn('default_image');
        });
    }
}
