<?php

namespace Tests\Console;

use App\Models\Post;
use Tests\TestCase;

class BackfillHoursSpentOnPostsTest extends TestCase
{
    public function testBackfillingHoursSpentOnPosts()
    {
        $postWithHours = factory(Post::class)->create([
            'details' => json_encode(['hours' => 1.5]),
        ]);

        $postWithTrickyHours = factory(Post::class)->create([
            'details' => json_encode(['trick!' => 'hours']),
        ]);

        $postWithHoursSpentAndHours = factory(Post::class)->create([
            'details' => json_encode(['hours' => 1.5]),
            'hours_spent' => 3.0,
        ]);

        $this->artisan('rogue:backfill-hours-spent-on-posts');

        $this->assertDatabaseHas('posts', [
            'id' => $postWithHours->id,
            'hours_spent' => 1.5,
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => $postWithTrickyHours->id,
            'hours_spent' => null,
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => $postWithHoursSpentAndHours->id,
            'hours_spent' => 3.0,
        ]);
    }
}
