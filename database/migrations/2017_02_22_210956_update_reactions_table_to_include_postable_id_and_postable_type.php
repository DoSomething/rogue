<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateReactionsTableToIncludePostableIdAndPostableType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reactions', function (Blueprint $table) {
            $table->renameColumn('reportback_item_id', 'postable_id');
            $table->string('postable_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reactions', function ($table) {
            $table->renameColumn('postable_id', 'reportback_item_id');
            $table->dropColumn('postable_type');
        });
    }
}
