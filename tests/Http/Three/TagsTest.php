<?php

namespace Tests\Http\Three;

use Tests\TestCase;
use Rogue\Models\Post;
use Rogue\Models\User;

class TagsTest extends TestCase
{
    /**
     * Test that a POST request to /tags updates the post's tags and
     * creates a new event and tagged entry.
     *
     * POST /tags
     * @return void
     */
    public function testTaggingAPost()
    {
        // Create the models that we will be using
        $post = factory(Post::class)->create();

        // Apply the tag to the post
        $response = $this->withAdminAccessToken()->postJson('api/v3/posts/' . $post->id . '/tag', [
            'tag_name' => 'Good Photo',
        ]);

        $response->assertStatus(200);

        // Make sure that the post's tags are updated.
        $this->assertContains('Good Photo', $post->tagNames());

        // @TODO: Make sure we created a event for the tag.
    }

    /**
     * Test that a POST request to /tags soft-deletes an existing tag
     * on a post, creates a new event, and tagged entry.
     *
     * POST /tags
     * @return void
     */
    // public function testDeleteTagOnAPost()
    // {
    //     $this->actingAsAdmin();

    //     // Create a post with a tag.
    //     $post = factory(Post::class)->create();
    //     $post->tag('Good Photo');

    //     $response = $this->postJson('tags', [
    //         'post_id' => $post->id,
    //         'tag_name' => 'Good Photo',
    //     ]);

    //     // Make sure that the tag is deleted.
    //     $response->assertStatus(200);
    //     $this->assertEmpty($post->tagNames());

    //     // Make sure we created an event for the tag.
    //     $this->assertDatabaseHas('events', [
    //         'eventable_type' => 'Rogue\Models\Post',
    //     ]);
    // }

    // /**
    //  * Test deleting one tag on a post only deletes that tag
    //  *
    //  * POST /tags
    //  * @return void
    //  */
    // public function testAddMultipleTagsAndDeleteOne()
    // {
    //     $this->actingAsAdmin();

    //     // Create a post with a tag.
    //     $post = factory(Post::class)->create();
    //     $post->tag('Good Photo');
    //     $post->tag('Tag To Delete');

    //     // Make sure both tags actually exist
    //     $this->assertContains('Good Photo', $post->tagNames());
    //     $this->assertContains('Tag To Delete', $post->tagNames());

    //     // Send request to remove "Tag To Delete" tag
    //     $response = $this->postJson('tags', [
    //         'post_id' => $post->id,
    //         'tag_name' => 'Tag To Delete',
    //     ]);

    //     // Make sure that the tag is deleted, but the other tag is still there
    //     $response->assertStatus(200);
    //     $this->assertContains('Good Photo', $post->tagNames());
    //     $this->assertNotContains('Tag To Delete', $post->fresh()->tagNames());

    //     // Make sure we created an event for the tag.
    //     $this->assertDatabaseHas('events', [
    //         'eventable_type' => 'Rogue\Models\Post',
    //     ]);
    // }

    // /**
    //  * Test that non-admin cannot tag or un-tag posts.
    //  *
    //  * @return void
    //  */
    // public function testUnauthenticatedUserCantTag()
    // {
    //     $user = factory(User::class)->create();
    //     $post = factory(Post::class)->create();

    //     $response = $this->actingAs($user)->postJson('tags', [
    //         'post_id' => $post->id,
    //         'tag_name' => 'Good Photo',
    //     ]);

    //     $response->assertStatus(403);
    // }

    // /**
    //  * Test post updated_at is updated when a new tag is applied to it
    //  *
    //  * @return void
    //  */
    // public function testPostTimestampUpdatedWhenTagAdded()
    // {
    //     // Create the models that we will be using
    //     $post = factory(Post::class)->create();

    //     // Later, apply the tag to the post
    //     $this->mockTime('10/21/2017 13:05:00');

    //     $this->actingAsAdmin()->postJson('tags', [
    //         'post_id' => $post->id,
    //         'tag_name' => 'Good Photo',
    //     ]);

    //     $this->assertEquals('2017-10-21 13:05:00', $post->fresh()->updated_at);
    // }

    // /**
    //  * Test post updated_at is updated when a new tag is applied to it
    //  *
    //  * @return void
    //  */
    // public function testWithoutTagScope()
    // {
    //     // Create the models that we will be using
    //     $posts = factory(Post::class, 20)->create();

    //     // Later, apply the tag to the post
    //     $this->actingAsAdmin()->postJson('tags', [
    //         'post_id' => $posts->first()->id,
    //         'tag_name' => 'get-outta-here',
    //     ]);

    //     $postsQuery = Post::withoutTag('get-outta-here')->get();

    //     $this->assertEquals(19, $postsQuery->count());
    // }
}
