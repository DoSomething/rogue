<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropUniqueNorthstarIdIndexReactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reactions', function (Blueprint $table) {
            $table->dropUnique('kudos_file_id_drupal_id_northstar_id_taxonomy_id_unique');
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
            $table->unique('kudos_file_id_drupal_id_northstar_id_taxonomy_id_unique');
        });
    }
}
