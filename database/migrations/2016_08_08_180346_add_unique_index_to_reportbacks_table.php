<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUniqueIndexToReportbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reportbacks', function (Blueprint $table) {
            $table->unique(['northstar_id', 'drupal_id', 'campaign_id', 'campaign_run_id'], 'user_campaign_run');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reportbacks', function (Blueprint $table) {
            $table->dropUnique('user_campaign_run');
        });
    }
}
