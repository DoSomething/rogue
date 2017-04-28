<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn('postable_type');
            $table->dropColumn('postable_id');
            $table->integer('post_id')->comment('Post Id of the post that has been reviewed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->string('postable_type')->comment('Type of the post that has been reviewed.');
            $table->integer('postable_id')->comment('Postable Id the of the post that has been reviewed.');

        });
    }
}
