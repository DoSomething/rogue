<?php

namespace Rogue\Repositories;

use Rogue\Models\Event;
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
        $event = Event::create([
            'northstar_id' => $data['northstar_id'],
            'event_type' => 'signup',
            'submission_type' => 'user',
        ]);

        $signup = $event->signup()->create([
            'event_id' => $event->id,
            'northstar_id' => $data['northstar_id'],
            'campaign_id' => $data['campaign_id'],
            'campaign_run_id' => $data['campaign_run_id'],
            'quantity' => null,
            'quantity_pending' => isset($data['quantity']) ? $data['quantity'] : null,
            'why_participated' => isset($data['why_participated']) ? $data['why_participated'] : null,
            'source' => $data['source'],
        ]);

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
