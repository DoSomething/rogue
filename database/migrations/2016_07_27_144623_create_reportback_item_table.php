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
            $table->integer('file_id')->index()->unsigned();
            $table->string('caption')->nullable();
            $table->string('status')->index()->nullable()->default('pending');
            $table->integer('reviewed')->unsigned()->nullable();
            $table->integer('reviewer')->unsigned()->nullable();
            $table->string('review_source')->nullable()->comment('Source URL which review was submitted from.');
            $table->string('source')->nullable()->comment('Source which reportback file was submitted from.');
            $table->ipAddress('remote_addr')->nullable()->comment('The IP address of the user that submitted the file.');
            $table->timestamps();

            $table->primary(['reportback_id', 'file_id']);
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
