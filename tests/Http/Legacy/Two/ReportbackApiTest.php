<?php

namespace Tests\Http\Api\Two;

use Tests\TestCase;
use Rogue\Models\Post;

class ReportbackApiTest extends TestCase
{
    /**
     * Test the "legacy" style reportback endpoint.
     *
     * GET api/v1/reportbacks
     */
    public function testGetReportbacks()
    {
        factory(Post::class, 'accepted', 10)->create();

        $response = $this->getJson('api/v1/reportbacks');

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
