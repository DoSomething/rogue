<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFilterByStateToGroupTypes extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('group_types', function (Blueprint $table) {
      $table
        ->boolean('filter_by_state')
        ->default(false)
        ->after('name')
        ->comment(
          'Whether or not group finders for this group type should filter by state.'
        );
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('group_types', function (Blueprint $table) {
      $table->dropColumn('filter_by_state');
    });
  }
}
