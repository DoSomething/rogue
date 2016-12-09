<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CascadeDeleteForiegnKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('signups', function ($table) {
            $table->dropForeign('signups_event_id_foreign');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        });

        Schema::table('reviews', function ($table) {
            $table->dropForeign('reviews_event_id_foreign');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        });

        Schema::table('photos', function ($table) {
            $table->dropForeign('photos_event_id_foreign');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('signups', function ($table) {
            $table->dropForeign('signups_event_id_foreign');
            $table->foreign('event_id')->references('id')->on('events');
        });

        Schema::table('reviews', function ($table) {
            $table->dropForeign('reviews_event_id_foreign');
            $table->foreign('event_id')->references('id')->on('events');
        });

        Schema::table('photos', function ($table) {
            $table->dropForeign('photos_event_id_foreign');
            $table->foreign('event_id')->references('id')->on('events');
        });
    }
}
