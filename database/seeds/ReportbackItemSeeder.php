<?php

use Rogue\Models\Reportback;
use Rogue\Models\ReportbackItem;
use Illuminate\Database\Seeder;

class ReportbackItemSeeder extends Seeder
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
            $numItems = rand(1, 4);

            for ($i = 0; $i < $numItems; $i++) {
                $reportbackItem = new ReportbackItem;

                $reportbackItem->reportback_id = $reportback->getKey();
                $reportbackItem->file_id = $faker->numberBetween(1, 9999);
                $reportbackItem->caption = $faker->text(100);
                $reportbackItem->status = 'pending';
                $reportbackItem->reviewed = null;
                $reportbackItem->reviewer = null;
                $reportbackItem->review_source = null;
                $reportbackItem->source = null;
                $reportbackItem->remote_addr = $faker->randomElement(['100.38.10.249', '207.110.19.130', '96.24.79.91']);

                $reportback->items()->save($reportbackItem);
            }
        }
    }
}
