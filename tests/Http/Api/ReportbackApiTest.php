<?php

namespace Tests\Http\Api;

use Rogue\Models\Post;
use Rogue\Models\Signup;
use Tests\BrowserKitTestCase;

class ReportbackApiTest extends BrowserKitTestCase
{
    public function testGetReportbacks()
    {
        $signups = factory(Signup::class, 10)->create();

        // Give each signup a post and give it status of "accepted"
        foreach ($signups as $signup) {
            $post = factory(Post::class)->create();
            $post->signup()->associate($signup);
            $post->campaign_id = $signup->campaign_id;
            $post->northstar_id = $signup->northstar_id;
            $post->status = 'accepted';
            $post->save();
        }

        $this->get('api/v1/reportbacks');

        $this->assertResponseStatus(200);
        $this->seeJsonStructure([
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
