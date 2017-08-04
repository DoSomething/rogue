<?php

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
    public function testAddingATagToAPost()
    {
        $post = factory(Post::class)->create();

        $this->actingAsAdmin()->post('tags', [
                'post_id' => $post->id,
                'tag_name' => 'Good Photo',
            ]);

        $this->assertResponseStatus(200);

        // Make sure that the post's tags are updated.
        $this->assertContains('Good Photo', $post->tagNames());

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
        $this->assertEmpty($post->tagNames());

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
}
