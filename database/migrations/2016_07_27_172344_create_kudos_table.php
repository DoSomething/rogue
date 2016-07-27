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
            $table->string('user_id')->comment('The rogue users.id that applied the kudos.');
            $table->integer('uid')->comment('The phoenix users.uid that applied the kudos.');
            $table->integer('fid')->comment('The reportback_file.fid that this kudos applies to.');
            $table->integer('tid')->comment('The taxonomy_term.tid that this kudos belongs to.');
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
