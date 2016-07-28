<?php

use Rogue\Models\Reportback;
use Illuminate\Database\Seeder;


class ReportbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Reportback::class, 3)->create();
    }
}
