<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSignupDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('signup_details', function (Blueprint $table) {
            $table->increments('id')->comment('Primary key.');
            $table->integer('signup_id')->unsigned()->index()->comment('The signup that owns these details.');
            $table->foreign('signup_id')->references('id')->on('signups')->onDelete('cascade');
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
        Schema::drop('signup_details');
    }
}
