<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->integer('event_id')->unsigned()->comment('The event this post corresponds to.');
            $table->foreign('event_id')->references('id')->on('events');

            $table->integer('signup_id')->unsigned()->comment('The signup this Post references.');
            $table->foreign('signup_id')->references('id')->on('signups');

            $table->string('northstar_id')->index()->comment('The users Northstar ID');
            $table->string('file_url')->comment('The url of the file on s3.');
            $table->string('edited_file_url')->nullable()->comment('The url of the edited file on s3.');
            $table->string('caption')->nullable();
            $table->string('status')->nullable()->default('pending');
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
        Schema::drop('photos');
    }
}
