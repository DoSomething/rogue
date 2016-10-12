<?php

use Rogue\Models\Reportback;
use Rogue\Models\ReportbackLog;
use Illuminate\Database\Seeder;

class ReportbackLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        $reportbacks = Reportback::all();

        foreach ($reportbacks as $reportback) {
            $log = new ReportbackLog;

            $log->reportback_id = $reportback->getKey();
            $log->northstar_id = $reportback->northstar_id;
            $log->drupal_id = $reportback->drupal_id;
            // At the time of seeding, lets assume these are all inserts.
            $log->op = 'insert';
            $log->quantity = $reportback->quantity;
            $log->why_participated = $reportback->why_participated;
            $log->files = $reportback->items->implode('file_url', ',');
            $log->num_files = $reportback->items->count();
            $log->remote_addr = $reportback->remote_addr;
            $log->reason = null;

            $log->save();
        }
    }
}
