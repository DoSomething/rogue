<?php

use Rogue\Models\Reaction;
use Rogue\Models\Reportback;
use Illuminate\Database\Seeder;
use Rogue\Models\ReportbackItem;

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
                $reportbackItem->file_url = 'https://placekitten.com/g/400/400';
                $reportbackItem->caption = $faker->text(100);
                $reportbackItem->status = 'pending';
                $reportbackItem->reviewed = null;
                $reportbackItem->reviewer = null;
                $reportbackItem->review_source = null;
                $reportbackItem->source = null;
                $reportbackItem->remote_addr = $faker->randomElement(['100.38.10.249', '207.110.19.130', '96.24.79.91']);

                $reportback->items()->save($reportbackItem);

                // Give this item a reaction.
                $reaction = Reaction::all()->random(1);
                $reportbackItem->reactions()->save($reaction);
            }
        }
    }
}
