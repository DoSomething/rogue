<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSecondaryCausesRunIdImpactDocAndMakeCausesNullableToCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->string('secondary_causes')->index()->after('cause')->nullable()->comment('Secondary causes associated with legacy campaigns');
            $table->integer('campaign_run_id')->index()->after('secondary_causes')->nullable()->comment('Campaign Run Id to reference when we run script to update signup and post ids after legacy campaign migration. This column can be deleted once migration and update script are complete.');
            $table->string('impact_doc')->after('campaign_run_id')->nullable()->comment('URL to proof of impact doc.');
            $table->string('cause')->nullable()->change();
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
            $table->dropColumn('impact_doc');
            $table->string('cause')->nullable(false)->change();
        });
    }
}
