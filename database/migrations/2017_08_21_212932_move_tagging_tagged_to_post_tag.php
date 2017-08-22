<?php

use Rogue\Models\Post;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MoveTaggingTaggedToPostTag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tags = DB::table('tagging_tagged')->get();

        foreach ($tags as $tag) {
            $post = Post::find($tag->taggable_id);
            $post->tag($tag->tag_name);
            // @TODO: decide if you need this call
            // $post->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('post_tag')->truncate();
    }
}
