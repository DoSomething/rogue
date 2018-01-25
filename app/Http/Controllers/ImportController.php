<?php

namespace Rogue\Http\Controllers;

use Carbon\Carbon;
use Rogue\Models\Post;
use League\Csv\Reader;
use Rogue\Models\Signup;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    /**
     * Instantiate a new ImportController instance.
     *
     * @param Rogue\Services\Registrar $registrar
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     *
     */
    public function show()
    {
        return view('pages.upload');
    }

    public function store(Request $request)
    {
        //@TODO - check type of csv import and store in variable we can use to fork off functionality
        // get file
        $upload = $request->file('upload-file');
        $filePath = $upload->getRealPath();
        $csv = Reader::createFromPath($filePath);
        $csv->setHeaderOffset(0);
        $header = $csv->getHeader();
        $records = $csv->getRecords();


        foreach ($records as $record) {
            // Clear referral code vars before processing new record.
            unset($northstarId);
            unset($campaignRunId);
            unset($source);

            $referralCode = $record['referral-code'];

            info('On record '.$record['id']);

            if ($referralCode) {
                $referralCode = explode(',', $referralCode);

                foreach ($referralCode as $value) {
                    $value = explode(':', $value);

                    // Grab northstar id
                    if (strtolower($value[0]) === 'user') {
                        $northstarId = $value[1];
                    }

                    // Grab the Campaign and Campaign Run Id.
                    if (strtolower($value[0]) === 'campaign') {
                        $campaignRunId = $value[1];
                    }

                    // Grab the source
                    if (strtolower($value[0]) === 'source') {
                        $source = $value[1];
                    }

                }

                if (isset($northstarId) && isset($campaignRunId)) {
                    // Check if a signup exists already.
                    $signup = Signup::where([
                        'northstar_id' => $northstarId,
                        'campaign_id' => 1111, // @TODO - hardcode grab the mic campaign id
                        'campaign_run_id' => 2222 ,// @TODO - hardcode grab the mic campaign run id
                    ])->first();

                    // If the signup doesn't exist, create one.
                    if (! $signup) {
                        info('No signup so we need to create one');
                        $signup = Signup::create([
                            'northstar_id' => $northstarId,
                            'campaign_id' => 1111, // @TODO - hardcode grab the mic campaign id
                            'campaign_run_id' => 2222, // @TODO - hardcode grab the mic campaign run id
                            'source' => "turbovote-import",
                        ]);
                    } else {
                        info('Signup exists already: '.$signup->id);
                    }

                    // Check if a post already exists.
                    $post = Post::where([
                        'signup_id' => $signup->id,
                        'northstar_id' => $northstarId,
                        'campaign_id' => 1111,
                        'type' => 'voter-reg',
                        // 'status' => $record['voter-registration-status'],
                    ])->first();

                    if (! $post) {
                        info('No post so we need to create one');
                        $tvCreatedAtMonth = strtolower(Carbon::parse($record['created-at'])->format('F'));
                        Post::create([
                            'signup_id' => $signup->id,
                            'campaign_id' => 1111, // @TODO - hardcode grab the mic campaign id
                            'northstar_id' => $northstarId,
                            'type' => 'voter-reg',
                            'action_bucket' => $tvCreatedAtMonth.'-turbovote',
                            'status' => $record['voter-registration-status'],
                        ]);
                    } else {
                        info('Post exists already: '.$post->id);

                        // Check if status is the same on the CSV record and the existing post, if not update the post with the new status.
                        if ($record['voter-registration-status'] !== $post->status) {
                            $post->status = $record['voter-registration-status'];
                            $post->save();
                        }
                    }
                } else {
                    info('Skipped record ' . $record['id'] . ' because no northstar id or campaign id');
                }

            } else { // @TODO - remove.
                info('Skipped record '.$record['id'].' because no referral code');
            }
        }
    }
}
