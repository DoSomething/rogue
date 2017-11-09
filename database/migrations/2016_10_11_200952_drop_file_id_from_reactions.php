<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropFileIdFromReactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reactions', function (Blueprint $table) {
            $table->dropColumn('file_id');

            $table->dropUnique('file_drupal_ns_taxonomy_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reactions', function (Blueprint $table) {
            $table->unique(['file_id', 'drupal_id', 'northstar_id', 'taxonomy_id'], 'file_drupal_ns_taxonomy_unique');

            $table->integer('file_id')->index()->comment('The reportback_file.fid that this kudos applies to.');
        });
    }
}
