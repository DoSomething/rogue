<?php

namespace Tests\Http;

use Tests\TestCase;
use Rogue\Models\Post;

class ImagesTest extends TestCase
{
    /**
     * Test for endpoint throttling.
     *
     * @return void
     */
    public function testImagesThrottle()
    {
        // Make a post to view
        $posts = factory(Post::class, 10)->create();

        // View the post 15 times (using 3 different versions)
        for ($i = 0; $i < 5; $i++) {
            $response = $this->getJson('images/' . $posts->random()->id)->assertSuccessful(200);
        }
        for ($i = 5; $i < 10; $i++) {
            $response = $this->getJson('images/' . $posts->random()->id . '?w=500&h=500&fit=crop');
            $response->assertStatus(200);
        }
        for ($i = 10; $i < 15; $i++) {
            $response = $this->getJson('images/' . $posts->random()->id . '?w=250&h=400&fit=crop&filt=sepia');
            $response->assertStatus(200);
        }

        // Get a "Too Many Attempts" response when viewing the post a 16th time
        $response = $this->getJson('images/' . $posts->random()->id);
        $response->assertStatus(429);
    }
}
