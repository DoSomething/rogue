<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reportback', function (Blueprint $table) {
            $table->increments('id')->comment('Primary key. Maps to rbid');
            $table->string('northstar_id')->comment('Users northstar id');
            $table->integer('drupal_id')->comment('The phoenix users.uid that reported back.');
            $table->integer('campaign_id')->comment('The campaign node id that the user has reported back for.');
            $table->integer('campaign_run_nid')->nullable()->comment('The campaign run id that the user reported back for.');
            $table->integer('quantity')->comment('The quantity of reportback_nouns reportback_verbed.');
            $table->text('why_participated')->nullable()->comment('Why the user participated.');
            $table->integer('num_participants')->nullable()->comment('The number of participants, if applicable.');
            $table->integer('flagged')->nullable()->comment('Whether the Reportback has been flagged.');
            $table->string('flagged_reason')->nullable()->comment('Reason why reportback was flagged.');
            $table->integer('promoted')->nullable()->comment('Whether the Reportback has been promoted.');
            $table->string('promoted_reason')->nullable()->comment('Reason why reportback was promoted.');
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
        Schema::drop('reportback');
    }
}
