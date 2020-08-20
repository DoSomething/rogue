<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropPendingQuantityFromSignups extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('signups', function (Blueprint $table) {
      $table->dropColumn('quantity_pending');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('signups', function (Blueprint $table) {
      $table
        ->integer('quantity_pending')
        ->nullable()
        ->comment('An unapproved quantity value.');
    });
  }
}
