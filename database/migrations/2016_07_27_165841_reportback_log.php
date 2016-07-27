<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReportbackLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reportback_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('reportback_id')->unsigned();
            $table->foreign('reportback_id')->references('id')->on('reportback')->onDelete('cascade');
            $table->integer('uid')->comment('The phoenix users.uid that updated.');
            $table->string('op')->nullable()->comment('Operation performed on the reportback.');
            $table->integer('quantity');
            $table->text('why_participated')->nullable();
            $table->string('files')->nullable()->comment('Comma separated list of file fids attached to reportback.');
            $table->integer('num_files')->comment('The number of files attached to reportback.');
            $table->ipAddress('remote_addr')->nullable()->comment('The IP address of the user that submitted the file.');
            $table->text('reason')->nullable()->comment('The reason the reoportback item was flagged/promoted');
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
        Schema::drop('reportback_log');
    }
}
