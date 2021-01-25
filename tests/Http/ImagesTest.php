<?php

namespace Tests\Http;

use App\Models\Post;
use Tests\TestCase;

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
        $posts = factory(Post::class, 10)
            ->states('photo', 'accepted')
            ->create();

        // View the post 60 times (using 3 different versions)
        for ($i = 0; $i < 5; $i++) {
            $response = $this->getJson(
                'images/' . $posts->random()->hash,
            )->assertSuccessful(200);
            $response->assertStatus(200);
        }
        for ($i = 5; $i < 10; $i++) {
            $response = $this->getJson(
                'images/' . $posts->random()->hash . '?w=500&h=500&fit=crop',
            );
            $response->assertStatus(200);
        }
        for ($i = 10; $i < 60; $i++) {
            $response = $this->getJson(
                'images/' .
                    $posts->random()->hash .
                    '?w=250&h=400&fit=crop&filt=sepia',
            );
            $response->assertStatus(200);
        }

        // Get a "Too Many Attempts" response when viewing the post a 61st time
        $response = $this->getJson('images/' . $posts->random()->id);
        $response->assertStatus(429);
    }

    /**
     * Test for CORS header with in response to whitelist all domains.
     *
     * @return void
     */
    public function testCorsWhitelist()
    {
        // Make a post to view.
        $posts = factory(Post::class, 1)
            ->states('photo', 'accepted')
            ->create();

        $response = $this->getJson(
            'images/' . $posts->random()->hash,
        )->assertSuccessful(200);

        // Get an Access-Control-Allow-Origin header with wildcard '*' in response.
        $response->assertHeader('Access-Control-Allow-Origin', '*');
    }
}
