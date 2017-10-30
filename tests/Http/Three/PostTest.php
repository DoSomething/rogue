<?php

namespace Tests\Http\Three;

use Rogue\Models\Post;
use Tests\BrowserKitTestCase;

class PostTest extends BrowserKitTestCase
{
    /**
     * Test for retrieving all posts.
     *
     * GET /api/v3/posts
     * @return void
     */
    public function testPostsIndex()
    {
        factory(Post::class, 10)->create();

        $this->get('api/v3/posts');

        $this->assertResponseStatus(200);
        $this->seeJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'signup_id',
                    'northstar_id',
                    'media' => [
                        'url',
                        'original_image_url',
                        'caption',
                    ],
                    'tags' => [],
                    'reactions' => [
                        'reacted',
                        'total',
                    ],
                    'status',
                    'source',
                    'remote_addr',
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
     * Test for retrieving a specific post.
     *
     * GET /api/v3/post/:post_id
     * @return void
     */
    public function testPostShow()
    {
        $post = factory(Post::class)->create();
        $response = $this->get('api/v3/posts/' . $post->id);

        $this->assertResponseStatus(200);
        $this->seeJsonStructure([
            'data' => [
                'id',
                'signup_id',
                'northstar_id',
                'media' => [
                    'url',
                    'original_image_url',
                    'caption',
                ],
                'tags' => [],
                'reactions' => [
                    'reacted',
                    'total',
                ],
                'status',
                'source',
                'remote_addr',
                'created_at',
                'updated_at',
            ],
        ]);

        $this->assertEquals($post->id, $this->response->getOriginalContent()['data']['id']);
    }

    /**
     * Test for updating a post successfully.
     *
     * PATCH /api/v3/posts/186
     * @return void
     */
    public function testUpdatingAPost()
    {
        $post = factory(Post::class)->create();

        $response = $this->withRogueApiKey()->json('PATCH', 'api/v3/posts/' . $post->id, [
            'status' => 'accepted',
            'caption' => 'new caption',
        ]);

        $this->assertResponseStatus(200);

        // Make sure that the posts's new status and caption gets persisted in the database.
        $this->assertEquals($post->fresh()->status, 'accepted');
        $this->assertEquals($post->fresh()->caption, 'new caption');
    }

}
