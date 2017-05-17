<?php

use Rogue\Models\Event;
use Rogue\Models\Post;
use Rogue\Models\User;
use Faker\Generator;

class TagsTest extends TestCase
{
    /*
     * Base URL for the Api.
     */
    protected $tagsUrl = 'tags';

    /**
     * Test that a PUT request to /tags updates the post's tags and creates a new event and tagged entry.
     *
     * POST /reviews
     * @return void
     */
    public function testAddingATagToAPost()
    {
        $user = factory(User::class)->make([
            'role' => 'admin',
        ]);

        $this->be($user);

        // Create a post.
        $post = factory(Post::class)->create();

        // Create a new tag.
        $tag = [
            'post_id' => $post->id,
            'tag_name' => 'Good Photo',
        ];

        $this->json('POST', $this->tagsUrl, $tag);
        $this->assertResponseStatus(200);

        // Make sure we created a event for the tag.
        $this->seeInDatabase('events', [
            'eventable_type' => 'Conner\Tagging\Model\Tagged',
        ]);

        // Make sure the tag is created.
        $this->seeInDatabase('tagging_tagged', [
            'taggable_id' => $post->id,
            'tag_name' => 'Good Photo',
        ]);

        // Make sure that the post's tags are updated.
        $this->assertContains('Good Photo', $post->tagNames());
    }

    /**
     * Test that a PUT request to /tags soft deletes an already existing tag on a post, creates a new event, and tagged entry.
     *
     * POST /tags
     * @return void
     */
    public function testSoftDeleteTagOnAPost()
    {
        $user = factory(User::class)->make([
            'role' => 'admin',
        ]);

        $this->be($user);

        // Create a post with a tag.
        $post = factory(Post::class)->create();
        $post->tag('Good Photo');

        // Soft delete tag.
        $tag = [
            'post_id' => $post->id,
            'tag_name' => 'Good Photo',
        ];

        $this->json('POST', $this->tagsUrl, $tag);
        $this->assertResponseStatus(200);

        // Make sure we created an event for the tag.
        $this->seeInDatabase('events', [
            'eventable_type' => 'Conner\Tagging\Model\Tagged',
        ]);

        // Make sure the tag is soft deleted.
        $this->notSeeInDatabase('tagging_tagged', [
            'deleted_at' => null,
        ]);

        // Make sure that the post's tags are updated.
        $this->assertEmpty($post->tagNames());
    }

    /**
     * Test that non-admin cannot tag or untag posts.
     *
     * @return void
     */
    public function testUnauthenticatedUserCantTag()
    {
        $user = factory(User::class)->make();

        $this->be($user);

        // Create a post.
        $post = factory(Post::class)->create();
        $tag = [
            'post_id' => $post->id,
            'tag_name' => 'Good Photo',
        ];

        $this->json('POST', $this->tagsUrl, $tag);
        $this->assertResponseStatus(403);
    }
}
