<?php

namespace Rogue\Jobs;

use Carbon\Carbon;
use Rogue\Models\Post;
use League\Csv\Reader;
use Rogue\Models\Signup;
use Illuminate\Bus\Queueable;
use Rogue\Services\Three\PostService;
use Illuminate\Queue\SerializesModels;
use Rogue\Services\Three\SignupService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Illuminate\Support\Facades\Storage;

class ImportTurboVotePosts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * The path to the stored csv.
     *
     * @var string
     */
    protected $filepath;

    /**
     * The role of the authenticated user that kicked off the request.
     *
     * @var object
     */
    protected $authenticatedUserRole;

    /**
      * Create a new job instance.
      *
      * @return void
      */
    public function __construct($filepath, $authenticatedUserRole)
    {
        $this->filepath = $filepath;

        $this->authenticatedUserRole = $authenticatedUserRole;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(SignupService $signupService, PostService $postService)
    {
        // Get the file from storage based on path.
        $file = Storage::get($this->filepath);

        // Read in the file and get the records.
        $csv = Reader::createFromString($file);
        $csv->setHeaderOffset(0);
        $records = $csv->getRecords();

        foreach ($records as $record) {
            info('Importing record ' . $record['id'], ['record' => $record]);

            $referralCode = $record['referral-code'];

            if ($referralCode) {
                $referralCodeValues = $this->parseReferralCode(explode(',', $referralCode));

                if (isset($referralCodeValues['northstar_id']) && isset($referralCodeValues['campaign_run_id'])) {
                    // Check if a signup exists already.
                    $signup = Signup::where([
                        'northstar_id' => $referralCodeValues['northstar_id'],
                        'campaign_id' => 1111, // @TODO - hardcode grab the mic campaign id or grab it from referral code
                        'campaign_run_id' => 2222 ,// @TODO - hardcode grab the mic campaign run id or grab it from referral code
                    ])->first();

                    // If the signup doesn't exist, create one.
                    if (! $signup) {
                        $signupData = [
                            'campaign_id' => 1111, // @TODO - hardcode grab the mic campaign id or grab it from referral code
                            'campaign_run_id' => 2222, // @TODO - hardcode grab the mic campaign run id or grab it from referral code
                            'source' => "turbovote-import",
                        ];

                        $signup = $signupService->create($signupData, $referralCodeValues['northstar_id']);
                    }

                    // Check if a post already exists.
                    $post = Post::where([
                        'signup_id' => $signup->id,
                        'northstar_id' => $referralCodeValues['northstar_id'],
                        'campaign_id' => 1111,
                        'type' => 'voter-reg',
                    ])->first();

                    if (! $post) {
                        $tvCreatedAtMonth = strtolower(Carbon::parse($record['created-at'])->format('F-Y'));

                        $postData = [
                            'campaign_id' => 1111, // @TODO - hardcode grab the mic campaign id
                            'northstar_id' => $referralCodeValues['northstar_id'],
                            'type' => 'voter-reg',
                            'action_bucket' => $tvCreatedAtMonth . '-turbovote',
                            'status' => $record['voter-registration-status'],
                            'source' => $referralCodeValues['source'],
                        ];

                        $post = $postService->create($postData, $signup->id, $this->authenticatedUserRole);
                    } else {
                        // If a post already exists, check if status is the same on the CSV record and the existing post,
                        // if not update the post with the new status.
                        if ($record['voter-registration-status'] !== $post->status) {
                            $post->status = $record['voter-registration-status'];
                            $post->save();
                        }
                    }
                } else {
                    info('Skipped record ' . $record['id'] . ' because no northstar id or campaign id is available.');
                }
            } else {
                info('Skipped record '.$record['id'].' because no referral code is available.');
            }
        }
    }

    /**
     * Parse the referral code field to grab individual values.
     * @TODO - update this to pull campaign and campaign run ID.
     *
     * @param  array $refferalCode
     */
    private function parseReferralCode($referralCode)
    {
        $values = [];

        foreach ($referralCode as $value) {
            $value = explode(':', $value);

            // Grab northstar id
            if (strtolower($value[0]) === 'user') {
                $values['northstar_id'] = $value[1];
            }

            // Grab the Campaign Run Id.
            if (strtolower($value[0]) === 'campaign') {
                $values['campaign_run_id'] = $value[1];
            }

            // Grab the source
            if (strtolower($value[0]) === 'source') {
                $values['source'] = $value[1];
            }
        }

        return $values;
    }
}
