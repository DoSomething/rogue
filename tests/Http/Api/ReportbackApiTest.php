<?php

namespace Tests\Http\Api;

use Tests\TestCase;
use Rogue\Models\Post;
use Rogue\Models\Signup;

class ReportbackApiTest extends TestCase
{
    /**
     * Test the "legacy" style reportback endpoint.
     *
     * GET api/v1/reportbacks
     */
    public function testGetReportbacks()
    {
        factory(Signup::class, 10)->create()
            ->each(function (Signup $signup) {
                // Give each signup a post and give it status of "accepted"
                $post = factory(Post::class)->make();
                $post->campaign_id = $signup->campaign_id;
                $post->northstar_id = $signup->northstar_id;
                $post->status = 'accepted';

                $signup->posts()->save($post);
            });

        $response = $this->get('api/v1/reportbacks');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'status',
                    'caption',
                    'uri',
                    'media' => ['uri', 'type'],
                    'tagged' => [],
                    'created_at',
                    'reportback' => ['id', 'created_at', 'updated_at', 'quantity', 'why_participated', 'flagged'],
                    'campaign',
                    'kudos' => [],
                    'user' => ['id'],
                ],
            ],
            'meta' => [
                'pagination' => [
                    'total',
                    'count',
                    'per_page',
                    'current_page',
                    'total_pages',
                    'links' => [],
                ],
            ],
        ]);
    }
}
