<?php

namespace Tests\Http;

use Rogue\Models\Tag;
use Rogue\Models\Post;
use Rogue\Models\User;
use Tests\BrowserKitTestCase;

class TagsTest extends BrowserKitTestCase
{
    /**
     * Test that a POST request to /tags updates the post's tags and
     * creates a new event and tagged entry.
     *
     * POST /tags
     * @return void
     */
    public function testAddingATagToAPost()
    {
        // Create the models that we will be using
        $post = factory(Post::class)->create();

        // Apply the tag to the post
        $this->actingAsAdmin()->post('tags', [
                'post_id' => $post->id,
                'tag_name' => 'Good Photo',
            ]);

        $this->assertResponseStatus(200);

        // Make sure that the post's tags are updated.
        $this->assertContains('Good Photo', $post->tags()->pluck('tag_name'));

        // Make sure we created a event for the tag.
        $this->seeInDatabase('events', [
            'eventable_type' => 'Rogue\Models\Post',
        ]);
    }

    /**
     * Test that a POST request to /tags soft-deletes an existing tag
     * on a post, creates a new event, and tagged entry.
     *
     * POST /tags
     * @return void
     */
    public function testDeleteTagOnAPost()
    {
        // @TODO: Tag model event fails if no authenticated user.
        $this->actingAsAdmin();

        // Create a post with a tag.
        $post = factory(Post::class)->create();
        $post->tag('Good Photo');

        $this->post('tags', [
            'post_id' => $post->id,
            'tag_name' => 'Good Photo',
        ]);

        // Make sure that the tag is deleted.
        $this->assertResponseStatus(200);
        $this->assertEmpty($post->tags()->pluck('tag_name'));

        // Make sure we created an event for the tag.
        $this->seeInDatabase('events', [
            'eventable_type' => 'Rogue\Models\Post',
        ]);
    }

    /**
     * Test deleting one tag on a post only deletes that tag
     *
     * POST /tags
     * @return void
     */
    public function testAddMultipleTagsAndDeleteOne()
    {
        // @TODO: Tag model event fails if no authenticated user.
        $this->actingAsAdmin();

        // Create a post with a tag.
        $post = factory(Post::class)->create();
        $post->tag('Good Photo');
        $post->tag('Tag To Delete');

        // Make sure both tags actually exist
        $this->seeInDatabase('post_tag', [
            'tag_id' => '1',
        ]);

        $this->seeInDatabase('post_tag', [
            'tag_id' => '2',
        ]);

        // Send request to remove "Tag To Delete" tag
        $this->post('tags', [
            'post_id' => $post->id,
            'tag_name' => 'Tag To Delete',
        ]);

        // Make sure that the tag is deleted, but the other tag is still there
        $this->assertResponseStatus(200);
        $this->assertContains('Good Photo', $post->tags()->pluck('tag_name'));
        $this->assertNotContains('Tag To Delete', $post->tags()->pluck('tag_name'));
        $this->notSeeInDatabase('post_tag', [
            'tag_id' => '2',
        ]);
        $this->seeInDatabase('post_tag', [
            'tag_id' => '1',
        ]);

        // Make sure we created an event for the tag.
        $this->seeInDatabase('events', [
            'eventable_type' => 'Rogue\Models\Post',
        ]);
    }

    /**
     * Test that non-admin cannot tag or un-tag posts.
     *
     * @return void
     */
    public function testUnauthenticatedUserCantTag()
    {
        $user = factory(User::class)->create();
        $post = factory(Post::class)->create();

        $this->actingAs($user)->post('tags', [
            'post_id' => $post->id,
            'tag_name' => 'Good Photo',
        ]);

        $this->assertResponseStatus(403);
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

        $this->actingAsAdmin()->post('tags', [
                'post_id' => $post->id,
                'tag_name' => 'Good Photo',
            ]);

        $this->assertEquals('2017-10-21 13:05:00', $post->fresh()->updated_at);
    }
}
