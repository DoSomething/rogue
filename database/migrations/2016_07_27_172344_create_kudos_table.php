<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKudosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kudos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('northstar_id')->comment('Users northstar id');
            $table->integer('drupal_id')->comment('The phoenix users.uid that applied the kudos.');
            $table->integer('file_id')->comment('The reportback_file.fid that this kudos applies to.');
            $table->integer('taxonomy_id')->comment('The taxonomy_term.tid that this kudos belongs to.');
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
        Schema::drop('kudos');
    }
}
