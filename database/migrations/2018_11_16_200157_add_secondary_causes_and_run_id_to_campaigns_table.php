<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSecondaryCausesAndRunIdToCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->string('secondary_causes')->index()->after('cause')->comment('Secondary causes associated with legacy campaigns');
            $table->integer('campaign_run_id')->index()->after('secondary_causes')->comment('Campaign Run Id to reference when we run script to update signup and post ids after legacy campaign migration. This column can be deleted once migration and update script are complete.');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn('secondary_causes');
            $table->dropColumn('campaign_run_id');
        });
    }
}
