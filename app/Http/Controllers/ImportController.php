<?php

namespace Rogue\Http\Controllers;

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
            $referralCode = $record['referral-code'];

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
            }

            if (isset($northstarId) && isset($campaignRunId)) {
                // Check if a signup exists already.
                $signup = Signup::where([
                    ['northstar_id' => $northstarId],
                    ['campaign_id' => 1111],
                    ['campaign_run_id' => 2222],
                ])->first();

                // If the signup doesn't exist, create one.
                if (! $signup) {
                    $signup = Signup::create([
                        'northstar_id' => $northstarId,
                        'campaign_id' => 1111, // @TODO - hardcode grab the mic campaign id
                        'campaign_run_id' => 2222, //$campaignRunId
                    ]);
                }

                // Check if a post already exists.
                $post = Post::where([
                    ['signup_id' => $northstarId],
                    ['campaign_id' => 1111],
                    ['northstar_id' => 2222],
                    ['type' => 'voter-reg'],
                    ['status' => $record['voter-registration-status']],
                ]);

                if (! $post) {
                    // Create new post record
                }
            }

        }
    }
}
