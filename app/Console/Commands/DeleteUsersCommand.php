<?php

namespace Rogue\Console\Commands;

use League\Csv\Reader;
use Rogue\Models\Post;
use Rogue\Models\Signup;
use Rogue\Services\Fastly;
use Illuminate\Console\Command;
use Rogue\Services\ImageStorage;

class DeleteUsersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rogue:delete {input=php://stdin} {--id_column=id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete signups and posts, given a CSV of user IDs.';

    /**
     * Execute the console command.
     *
     * @param ImageStorage $storage
     * @param Fastly $fastly
     *
     * @return mixed
     */
    public function handle(ImageStorage $storage, Fastly $fastly)
    {
        $input = file_get_contents($this->argument('input'));
        $csv = Reader::createFromString($input);
        $csv->setHeaderOffset(0);

        info('Deleting activity for ' . count($csv) . ' users...');

        foreach ($csv->getRecords() as $record) {
            $id = $record[$this->option('id_column')];

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
                $storage->deleteImage($post->url);
                $fastly->purgeKey('post-'.$post->id);

                $post->update([
                    'text' => null,
                    'details' => null,
                    'url' => null,
                ]);

                $post->delete();
            }

            info('Deleted: ' . $id . '(' . $posts->count() . ' posts and ' . $signups->count() . ' signups)');
        }

        info('Done!');
    }
}
