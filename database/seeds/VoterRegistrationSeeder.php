<?php

use Rogue\Models\Post;
use Rogue\Models\Group;
use Rogue\Models\Action;
use Rogue\Models\Campaign;
use Rogue\Models\GroupType;
use Illuminate\Database\Seeder;

class VoterRegistrationSeeder extends Seeder
{
  /**
   * Seed database with sample voter registration campaign, action, and group posts.
   *
   * @return void
   */
  public function run()
  {
    $campaign = factory(Campaign::class)
      ->states('voter-registration')
      ->create();

    $action = factory(Action::class)
      ->states('voter-registration')
      ->create([
        'campaign_id' => $campaign->id,
      ]);

    $groupType = factory(GroupType::class)->create();

    factory(Group::class, 100)
      ->states('school')
      ->create(['group_type_id' => $groupType->id])
      ->each(function (Group $group) use ($action) {
        // Create completed voter registrations for this group.
        factory(Post::class, rand(1, 40))
          ->states('voter-reg', 'register-form')
          ->create([
            'action_id' => $action->id,
            'group_id' => $group->id,
          ]);
      });
  }
}
