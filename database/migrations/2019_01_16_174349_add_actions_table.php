<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255)->index()->comment('The action name of the post.');
            $table->integer('campaign_id')->index()->comment('The campaign id the post is associated to.');
            $table->string('post_type', 255)->index()->comment('The type of post the post is (e.g. photo, text, voter-reg, etc).');
            $table->boolean('reportback')->comment('Whether or not the post should count as a reportback.');
            $table->boolean('civic_action')->comment('Whether or not the post should count as a civic action.');
            $table->boolean('scholarship_entry')->comment('Whether or not the post should count as a scholarship entry.');
            $table->string('legacy_action_name', 255)->index()->nullable()->comment('The action from the posts table pre post metadata.');
            $table->boolean('active')->comment('Whether or not the action is active.');
            $table->string('noun', 255)->nullable()->comment('The noun of the post (e.g. jeans).');
            $table->string('verb', 255)->nullable()->comment('The verb of of the post (e.g. donated).');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tagging_tagged');
    }
}
