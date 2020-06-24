<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class addCityStateExternalIdToGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->string('external_id')->nullable()->after('name');
            $table->string('city')->nullable()->after('external_id');
            $table->string('state', 10)->nullable()->after('city');
            $table->index(['group_type_id', 'state']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropIndex(['group_type_id', 'state']);
            $table->dropColumn('external_id');
            $table->dropColumn('city');
            $table->dropColumn('state');
        });
    }
}
