<?php

use Rogue\Models\User;
use Rogue\Models\Post;
use Rogue\Models\Signup;

class PostTest extends TestCase
{
    /**
     * Test that posts get soft deleted when hiting the DELETE endpoint.
     *
     * @return void
     */
    public function testDeletingAPost()
    {
        $user = factory(User::class)->make([
            'role' => 'admin',
        ]);

        $this->be($user);

        $post = factory(Post::class)->create();

        $this->json('DELETE', 'posts/' . $post->id);

        $this->assertResponseStatus(200);

        // Check that the post record is still in the database
        // Also, check that you can't find it with a `deleted_at` column as null.
        $this->seeInDatabase('posts', [
                'id' => $post->id,
                'url' => null,
            ])->notSeeInDatabase('posts', [
                'id' => $post->id,
                'deleted_at' => null,
            ]);
    }

    /**
     * Test that non-admins can't delete posts.
     *
     * @return void
     */
    public function testUnauthenticatedUserDeletingAPost()
    {
        $user = factory(User::class)->make();

        $this->be($user);

        $post = factory(Post::class)->create();

        $this->json('DELETE', 'posts/' . $post->id);

        $this->assertResponseStatus(403);
    }

    /**
     * Test that a signup's updated_at updates when a post is updated.
     *
     * @return void
     */
    public function testUpdatingSignupTimestampWhenPostIsUpdated()
    {
        // Create a signup and a post, and associate them to each other.
        $signup = factory(Signup::class)->create();
        $post = factory(Post::class)->create();
        $post->signup()->associate($signup);

        // Wait 1 second before updating the caption to make sure the created_at and updated_at times are different.
        sleep(2);

        // Update the caption of the post.
        $post->caption = 'new caption';
        $post->save();

        // Re-grab the updated signup from the database.
        $updatedSignup = Signup::where('id', $signup->id)->first();

        // Make sure the signup and post's updated_at matches.
        $this->assertEquals($post->updated_at, $updatedSignup->updated_at);
    }
}
