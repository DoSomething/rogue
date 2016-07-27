<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportbackItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reportback_item', function (Blueprint $table) {
            $table->integer('reportback_id')->unsigned();
            $table->foreign('reportback_id')->references('id')->on('reportback')->onDelete('cascade');
            $table->integer('fid')->unsigned();
            $table->ipAddress('remote_addr')->nullable()->comment('The IP address of the user that submitted the file.');
            $table->string('caption')->nullable();
            $table->string('status')->nullable()->default('pending');
            $table->integer('reviewed')->unsigned()->nullable();
            $table->integer('reviewer')->unsigned()->nullable();
            $table->string('review_source')->nullable()->comment('Source URL which review was submitted from.');
            $table->string('source')->nullable()->comment('Source which reportback file was submitted from.');
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
        Schema::drop('reportback_item');
    }
}
