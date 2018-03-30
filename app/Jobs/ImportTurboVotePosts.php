<?php

namespace Rogue\Jobs;

use Carbon\Carbon;
use League\Csv\Reader;
use Rogue\Models\Post;
use Rogue\Models\Signup;
use Illuminate\Bus\Queueable;
use Rogue\Services\PostService;
use Illuminate\Support\Facades\Storage;
use Rogue\Services\SignupService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

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
        // A little hack to Make sure we can run through large files.
        set_time_limit(120);

        // Get the file from storage based on path.
        $file = Storage::get($this->filepath);

        // Read in the file and get the records.
        $csv = Reader::createFromString($file);
        $csv->setHeaderOffset(0);
        $records = $csv->getRecords();

        foreach ($records as $record) {
            info('Importing record ' . $record['id']);

            $referralCode = $record['referral-code'];

            if ($referralCode) {
                $referralCodeValues = $this->parseReferralCode(explode(',', $referralCode));

                // Fall back to the Grab The Mic campaign (campaign_id: 8017, campaign_run_id: 8022)
                // if these keys are not present.
                $referralCodeValues['campaign_id'] = ! isset($referralCodeValues['campaign_id']) ? '8017' : $referralCodeValues['campaign_id'];
                $referralCodeValues['campaign_run_id'] = ! isset($referralCodeValues['campaign_run_id']) ? '8022' : $referralCodeValues['campaign_run_id'];

                if (isset($referralCodeValues['northstar_id'])) {
                    // Check if a signup exists already.
                    $signup = Signup::where([
                        'northstar_id' => $referralCodeValues['northstar_id'],
                        'campaign_id' => $referralCodeValues['campaign_id'],
                        'campaign_run_id' => $referralCodeValues['campaign_run_id'],
                    ])->first();

                    // If the signup doesn't exist, create one.
                    if (! $signup) {
                        $signupData = [
                            'campaign_id' => $referralCodeValues['campaign_id'],
                            'campaign_run_id' => $referralCodeValues['campaign_run_id'],
                            'source' => 'turbovote-import',
                        ];

                        $signup = $signupService->create($signupData, $referralCodeValues['northstar_id']);
                    }

                    // Check if a post already exists.
                    $post = Post::where([
                        'signup_id' => $signup->id,
                        'northstar_id' => $referralCodeValues['northstar_id'],
                        'campaign_id' => $referralCodeValues['campaign_id'],
                        'type' => 'voter-reg',
                    ])->first();

                    // If the post doesn't exist, create one.
                    if (! $post) {
                        $tvCreatedAtMonth = strtolower(Carbon::parse($record['created-at'])->format('F-Y'));
                        $sourceDetails = isset($referralCodeValues['source_details']) ? $referralCodeValues['source_details'] : null;
                        $postDetails = $this->extractDetails($record);

                        $postData = [
                            'campaign_id' => $referralCodeValues['campaign_id'],
                            'northstar_id' => $referralCodeValues['northstar_id'],
                            'type' => 'voter-reg',
                            'action' => $tvCreatedAtMonth . '-turbovote',
                            'status' => $record['voter-registration-status'],
                            'source' => $referralCodeValues['source'],
                            'source_details' => $sourceDetails,
                            'details' => $postDetails,
                        ];

                        $post = $postService->create($postData, $signup->id, $this->authenticatedUserRole);
                    } else {
                        // If a post already exists, check if status is the same on the CSV record and the existing post,
                        // if not update the post with the new status.
                        if ($record['voter-registration-status'] !== $post->status) {
                            $postService->update($post, ['status' => $record['voter-registration-status']]);
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
     * Parse the record for extra details and return them as a JSON object.
     *
     * @param  array $record
     * @param  array $extraData
     */
    private function extractDetails($record, $extraData = null)
    {
        $details = [];

        $importantKeys = [
            'hostname',
            'referral-code',
            'partner-comms-opt-in',
            'created-at',
            'updated-at',
            'voter-registration-status',
            'voter-registration-source',
            'voter-registration-method',
            'voting-method-preference',
            'email subscribed',
            'sms subscribed',
        ];

        foreach ($importantKeys as $key) {
            $details[$key] = $record[$key];
        }

        if ($extraData) {
            $details = array_merge($details, $extraData);
        }

        return json_encode($details);
    }

    /**
     * Parse the referral code field to grab individual values.
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

            // Grab the Campaign Id.
            if (strtolower($value[0]) === 'campaignid') {
                $values['campaign_id'] = $value[1];
            }

            // Grab the Campaign Run Id.
            if (strtolower($value[0]) === 'campaignrunid') {
                $values['campaign_run_id'] = $value[1];
            }

            // Grab the source
            if (strtolower($value[0]) === 'source') {
                $values['source'] = $value[1];
            }

            // Grab any source details
            if (strtolower($value[0]) === 'source_details') {
                $values['source_details'] = $value[1];
            }
        }

        return $values;
    }
}
