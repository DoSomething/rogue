<?php

use Rogue\Models\Reaction;
use Illuminate\Database\Seeder;

class ReactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Reaction::class, 10)->create();
    }
}
