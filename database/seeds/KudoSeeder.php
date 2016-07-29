<?php

use Rogue\Models\Kudo;
use Illuminate\Database\Seeder;

class KudoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Kudo::class, 10)->create();
    }
}
