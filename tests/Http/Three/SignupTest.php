<?php

namespace Tests\Http\Three;

use Rogue\Models\Signup;
use Rogue\Models\Post;
use Tests\BrowserKitTestCase;

class SignupTest extends BrowserKitTestCase
{
    /**
     * Test for retrieving all signups.
     *
     * GET /api/v3/signups
     * @return void
     */
    public function testSignupsIndex()
    {
        factory(Signup::class, 10)->create();

        $this->get('api/v3/signups');

        $this->assertResponseStatus(200);
        $this->seeJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'northstar_id',
                    'campaign_id',
                    'campaign_run_id',
                    'quantity',
                    'why_participated',
                    'source',
                    'details',
                    'created_at',
                    'updated_at',
                ],
            ],
            'meta' => [
                'pagination' => [
                    'total',
                    'count',
                    'per_page',
                    'current_page',
                    'total_pages',
                    'links',
                ],
            ],
        ]);
    }

    /**
     * Test for retrieving a specific signup.
     *
     * GET /api/v3/signups/:signup_id
     * @return void
     */
    public function testSignupShow()
    {
        $signup = factory(Signup::class)->create();
        $this->get('api/v3/signups/' . $signup->id);

        $this->assertResponseStatus(200);
        $this->seeJsonStructure([
            'data' => [
                'id',
                'northstar_id',
                'campaign_id',
                'campaign_run_id',
                'quantity',
                'why_participated',
                'source',
                'details',
                'created_at',
                'updated_at',
            ],
        ]);
    }


    /**
     * Test for retrieving a signup with included post info.
     *
     * GET /api/v3/signups/186?include=posts
     * @return void
     */
    public function testSignupWithIncludedPosts()
    {
        $signup = factory(Signup::class)->create();
        $post = factory(Post::class)->create();
        $post->signup()->associate($signup);
        $post->campaign_id = $signup->campaign_id;
        $post->northstar_id = $signup->northstar_id;
        $post->save();


        $this->get('api/v3/signups/' . $signup->id . '?include=posts');
        $this->assertResponseStatus(200);

        $this->seeJsonStructure([
            'data' => [
                'posts' => [
                    'data' => [
                        '*' => [
                            'id',
                        ],
                    ],
                ],
            ],
        ]);
    }
}
