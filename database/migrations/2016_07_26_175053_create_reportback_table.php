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
            $table->string('user_id')->comment('The rogue users.id that correlates to their Northstar id');
            $table->integer('uid')->comment('The phoenix users.uid that reported back.');
            $table->integer('nid')->comment('The node.nid that the user has reported back for.');
            $table->integer('run_nid')->nullable()->comment('The node.run_nid that the user reported back for.');
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
