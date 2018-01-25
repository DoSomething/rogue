<?php

namespace Rogue\Http\Controllers;

use Carbon\Carbon;
use Rogue\Models\Post;
use League\Csv\Reader;
use Rogue\Models\Signup;
use Illuminate\Http\Request;
use Rogue\Services\Three\SignupService;

class ImportController extends Controller
{
    /*
     * SignupServices Instance
     *
     * @var Rogue\Services\Three\SignupService;
     */
    protected $signupService;

    /**
     * Instantiate a new ImportController instance.
     *
     * @param Rogue\Services\Registrar $registrar
     */
    public function __construct(SignupService $signupService)
    {
        $this->middleware('auth');

        $this->signupService = $signupService;
    }

    /**
     * Show the upload form.
     */
    public function show()
    {
        return view('pages.upload');
    }

    /**
     * Import the uploaded file.
     *
     * @param  Request $request
     */
    public function store(Request $request)
    {
        $request->validate([
            'upload-file' => 'required',
            // 'type' => 'required',
        ]);

        //@TODO - check type of csv import and store in variable we can use to fork off functionality
        // get file
        $upload = $request->file('upload-file');
        $filePath = $upload->getRealPath();
        $csv = Reader::createFromPath($filePath);
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

                       $signup = $this->signupService->create($signupData, $referralCodeValues['northstar_id']);

                        info('signup_created', ['id' => $signup->id, 'northstar_id' => $signup->northstar_id]);
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

                        $post = Post::create([
                            'signup_id' => $signup->id,
                            'campaign_id' => 1111, // @TODO - hardcode grab the mic campaign id
                            'northstar_id' => $referralCodeValues['northstar_id'],
                            'type' => 'voter-reg',
                            'action_bucket' => $tvCreatedAtMonth.'-turbovote',
                            'status' => $record['voter-registration-status'],
                        ]);

                        info('post_created', ['id' => $post->id, 'signup_id' => $post->signup_id]);
                    } else {
                        // If a post already exists, check if status is the same on the CSV record and the existing post,
                        // if not update the post with the new status.
                        if ($record['voter-registration-status'] !== $post->status) {
                            $post->status = $record['voter-registration-status'];
                            $post->save();
                        }
                    }
                } else {
                    info('Skipped record ' . $record['id'] . ' because no northstar id or campaign is available.');
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
