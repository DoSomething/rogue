<?php

namespace Rogue\Http\Controllers;

use Rogue\Models\Post;
use Rogue\Models\Signup;
use Rogue\Services\Fastly;
use Rogue\Services\ImageStorage;

class UsersController extends Controller
{
    /**
     * Create a controller instance.
     */
    public function __construct(ImageStorage $storage, Fastly $fastly)
    {
        $this->storage = $storage;
        $this->fastly = $fastly;

        $this->middleware('auth:api');
        $this->middleware('role:admin,staff');
        $this->middleware('scopes:write');
        $this->middleware('scopes:activity');
    }

    /**
     * Delete the given user's activity.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Find the user's signups, anonymize, and soft-delete.
        $signups = Signup::withTrashed()->where('northstar_id', $id);
        foreach ($signups->cursor() as $signup) {
            $signup->update([
                'why_participated' => null,
                'details' => null,
            ]);

            $signup->delete();
        }

        // Find the user's posts, anonymize & delete images, and soft-delete:
        $posts = Post::withTrashed()->where('northstar_id', $id);
        foreach ($posts->cursor() as $post) {
            $this->storage->delete($post);
            $this->fastly->purge($post);

            $post->update([
                'text' => null,
                'details' => null,
                'url' => null,
            ]);

            $post->delete();
        }

        info(
            'Deleted: ' .
                $id .
                '(' .
                $posts->count() .
                ' posts and ' .
                $signups->count() .
                ' signups)'
        );

        return $this->respond('All signups & posts deleted.');
    }
}
