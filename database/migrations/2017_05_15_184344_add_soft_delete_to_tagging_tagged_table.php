<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftDeleteToTaggingTaggedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tagging_tagged', function (Blueprint $table) {
            $table->string('admin_northstar_id')->index()->comment('The Northstar Id of the admin who added or deleted the tag(s)');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tagging_tagged', function (Blueprint $table) {
            $table->dropColumn('admin_northstar_id');
            $table->dropTimestamps();
            $table->dropSoftDeletes();
        });
    }
}
