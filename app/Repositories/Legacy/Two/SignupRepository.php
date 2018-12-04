<?php

namespace Rogue\Repositories\Legacy\Two;

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

        $signup->northstar_id = $data['northstar_id'];
        $signup->campaign_id = $data['campaign_id'];
        $signup->campaign_run_id = isset($data['campaign_run_id']) ? $data['campaign_run_id'] : null;
        $signup->quantity = isset($data['quantity']) ? $data['quantity'] : null;
        $signup->why_participated = isset($data['why_participated']) ? $data['why_participated'] : null;
        $signup->source = isset($data['source']) ? $data['source'] : null;
        $signup->source_details = isset($data['source_details']) ? $data['source_details'] : null;
        $signup->details = isset($data['details']) ? $data['details'] : null;

        if (isset($data['created_at'])) {
            // Manually set created and updated times for the signup
            $signup->created_at = $data['created_at'];
            $signup->updated_at = isset($data['updated_at']) ? $data['updated_at'] : $data['created_at'];
            $signup->save(['timestamps' => false]);

            // Manually update the signup event timestamp.
            $event = $signup->events->first();
            $event->created_at = $data['created_at'];
            $event->updated_at = isset($data['updated_at']) ? $data['updated_at'] : $data['created_at'];
            $event->save(['timestamps' => false]);
        } else {
            $signup->save();
        }

        return $signup;
    }

    /**
     * Get a signup
     *
     * @param  string $northstarId
     * @param  int $campaignId
     * @param  int $campaignRunId
     * @return \Rogue\Models\Signup|null
     */
    public function get($northstarId, $campaignId)
    {
        $signup = Signup::where([
            'northstar_id' => $northstarId,
            'campaign_id' => $campaignId,
        ])->orderByRaw('campaign_run_id IS NULL')
            ->orderBy('campaign_run_id', 'desc')
            ->first();

        return $signup;
    }
}
