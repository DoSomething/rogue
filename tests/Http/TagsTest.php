<?php

namespace Tests\Http;

use Tests\TestCase;
use Rogue\Models\Tag;
use Rogue\Models\Post;

class TagsTest extends TestCase
{
    /**
     * Test that a POST request to /tags updates the post's tags and
     * creates a new event and tagged entry.
     *
     * POST /v3/posts/:post_id/tag
     * @return void
     */
    public function testTaggingAndUntaggingAPost()
    {
        // Create the models that we will be using
        $post = factory(Post::class)->create();

        // Apply the tag to the post
        $response = $this->withAdminAccessToken()->postJson('api/v3/posts/' . $post->id . '/tags', [
            'tag_name' => 'Good Submission',
        ]);

        $response->assertStatus(200);

        // Make sure that the post's tags are updated.
        $this->assertContains('Good Submission', $post->tagNames());

        // Untag the post
        $response = $this->withAdminAccessToken()->postJson('api/v3/posts/' . $post->id . '/tags', [
            'tag_name' => 'Good Submission',
        ]);

        $response->assertStatus(200);

        // Make sure that the post's tags are updated.
        $this->assertEmpty($post->fresh()->tagNames());

        // @TODO: Make sure we created a event for the tag once events are refactored.
    }

    /**
     * Test a POST request to /tags without the activity scope.
     *
     * POST /v3/posts/:post_id/tag
     * @return void
     */
    public function testTaggingAPostWithoutActivityScope()
    {
        // Create the models that we will be using
        $post = factory(Post::class)->create();

        // Apply the tag to the post
        $response = $this->postJson('api/v3/posts/' . $post->id . '/tags', [
            'tag_name' => 'Good Submission',
        ]);

        $response->assertStatus(401);
        $this->assertEquals('Unauthenticated.', $response->decodeResponseJson()['message']);
    }

    /**
     * Test that a non-admin cannot tag a post.
     *
     * POST /v3/posts/:post_id/tag
     * @return void
     */
    public function testUnauthenticatedUserCannotTagAPost()
    {
        // Create the models that we will be using
        $post = factory(Post::class)->create();

        // Apply the tag to the post
        $response = $this->postJson('api/v3/posts/' . $post->id . '/tags', [
            'tag_name' => 'Good Submission',
        ]);

        $response->assertStatus(401);
    }

    /**
     * Test deleting one tag on a post only deletes that tag
     *
     * POST /posts/:post_id/tag
     * @return void
     */
    public function testAddMultipleTagsAndDeleteOne()
    {
        // Create a post with tags.
        $post = factory(Post::class)->create();
        $post->tag('Good Submission');
        $post->tag('Tag To Delete');
        $post = $post->fresh();

        // Make sure both tags actually exist
        $this->assertContains('Good Submission', $post->tagNames());
        $this->assertContains('Tag To Delete', $post->tagNames());

        // Send request to remove "Tag To Delete" tag
        $response = $this->withAdminAccessToken()->postJson('api/v3/posts/' . $post->id . '/tags', [
            'tag_name' => 'Tag To Delete',
        ]);

        // Make sure that the tag is deleted, but the other tag is still there
        $post = $post->fresh();
        $response->assertStatus(200);
        $this->assertContains('Good Submission', $post->tagNames());
        $this->assertNotContains('Tag To Delete', $post->tagNames());

        // @TODO: When we refactor events, make sure we created an event for the tag that was deleted.
    }

    /**
     * Test post updated_at is updated when a new tag is applied to it
     *
     * @return void
     */
    public function testPostTimestampUpdatedWhenTagAdded()
    {
        // Create the models that we will be using
        $post = factory(Post::class)->create();

        // Later, apply the tag to the post
        $this->mockTime('10/21/2017 13:05:00');

        $this->withAdminAccessToken()->postJson('api/v3/posts/' . $post->id . '/tags', [
            'tag_name' => 'Good Submission',
        ]);

        $this->assertEquals('2017-10-21 13:05:00', $post->fresh()->updated_at);
    }

    /**
     * Test withoutTag scope
     *
     * @return void
     */
    public function testWithoutTagScope()
    {
        // Create the models that we will be using
        $posts = factory(Post::class, 20)->create();

        // Later, apply the tag to the post
        $this->withAdminAccessToken()->postJson('api/v3/posts/' . $posts->first()->id . '/tags', [
            'tag_name' => 'get-outta-here',
        ]);

        $postsQuery = Post::withoutTag('get-outta-here')->get();

        $this->assertEquals(19, $postsQuery->count());
    }
}
