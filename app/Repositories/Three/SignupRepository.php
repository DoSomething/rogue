<?php

namespace Rogue\Repositories\Three;

use Rogue\Models\Signup;

class SignupRepository
{
    /**
     * Create a signup.
     *
     * @param  array $data
     * @return \Rogue\Models\Signup|null
     */
    public function create($data)
    {
        // Create the signup
        $signup = new Signup;

        // @TODO change auth()->id(); based on Dave's note:
        // It might make sense to send these along as parameters from the controller
        // (so that we could use the repository for things that aren't HTTP requests,
        // like for creating signups from a CSV or something).
        $signup->northstar_id = auth()->id();
        $signup->campaign_id = $data['campaign_id'];
        $signup->campaign_run_id = isset($data['campaign_run_id']) ? $data['campaign_run_id'] : null;
        $signup->why_participated = isset($data['why_participated']) ? $data['why_participated'] : null;
        $signup->source = token()->client();
        $signup->details = isset($data['details']) ? $data['details'] : null;
        $signup->save();

        return $signup;
    }

    /**
     * Get a signup based on unique fields.
     *
     * @param  string $northstarId
     * @param  int $campaignId
     * @param  int $campaignRunId
     * @return \Rogue\Models\Signup|null
     */
    public function get($northstarId, $campaignId, $campaignRunId)
    {
        $signup = Signup::where([
            'northstar_id' => $northstarId,
            'campaign_id' => $campaignId,
            'campaign_run_id' => $campaignRunId,
        ])->first();

        return $signup;
    }
}
