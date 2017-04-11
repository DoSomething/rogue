<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropReportbacksItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('reportback_logs');

        Schema::drop('reportback_items');

        Schema::drop('reportbacks');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('reportbacks', function (Blueprint $table) {
            $table->increments('id')->comment('Primary key. Maps to rbid');
            $table->string('northstar_id')->index()->comment('Users northstar id');
            $table->integer('drupal_id')->index()->comment('The phoenix users.uid that reported back.');
            $table->integer('campaign_id')->index()->comment('The campaign node id that the user has reported back for.');
            $table->integer('campaign_run_id')->index()->nullable()->comment('The campaign run id that the user reported back for.');
            $table->integer('quantity')->comment('The quantity of reportback_nouns reportback_verbed.');
            $table->text('why_participated')->nullable()->comment('Why the user participated.');
            $table->integer('num_participants')->nullable()->comment('The number of participants, if applicable.');
            $table->integer('flagged')->nullable()->comment('Whether the Reportback has been flagged.');
            $table->string('flagged_reason')->nullable()->comment('Reason why reportback was flagged.');
            $table->integer('promoted')->nullable()->comment('Whether the Reportback has been promoted.');
            $table->string('promoted_reason')->nullable()->comment('Reason why reportback was promoted.');
            $table->timestamps();
        });

        Schema::create('reportback_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('reportback_id')->unsigned();
            $table->foreign('reportback_id')->references('id')->on('reportbacks')->onDelete('cascade');
            $table->integer('file_id')->index()->unsigned();
            $table->string('caption')->nullable();
            $table->string('status')->index()->nullable()->default('pending');
            $table->integer('reviewed')->unsigned()->nullable();
            $table->integer('reviewer')->unsigned()->nullable();
            $table->string('review_source')->nullable()->comment('Source URL which review was submitted from.');
            $table->string('source')->nullable()->comment('Source which reportback file was submitted from.');
            $table->ipAddress('remote_addr')->nullable()->comment('The IP address of the user that submitted the file.');
            $table->timestamps();
        });

        Schema::create('reportback_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('reportback_id')->unsigned();
            $table->foreign('reportback_id')->references('id')->on('reportbacks')->onDelete('cascade');
            $table->string('northstar_id')->comment('The rogue users.id that updated.');
            $table->integer('drupal_id')->comment('The phoenix users.uid that updated.');
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
}
