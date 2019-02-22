<?php

namespace Rogue\Repositories;

use Rogue\Models\Signup;

class SignupRepository
{
    /**
     * Create a signup.
     *
     * @param  array $data
     * @param  string $northstarId
     * @return \Rogue\Models\Signup|null
     */
    public function create($data, $northstarId)
    {
        // Create the signup
        $signup = new Signup;

        $signup->northstar_id = $northstarId;
        $signup->campaign_id = $data['campaign_id'] ? $data['campaign_id'] : get_campaign_by_action_id($data['action_id'])->id;
        $signup->why_participated = isset($data['why_participated']) ? $data['why_participated'] : null;
        $signup->source = isset($data['source']) ? $data['source'] : token()->client();
        $signup->source_details = isset($data['source_details']) ? $data['source_details'] : null;
        $signup->details = isset($data['details']) ? $data['details'] : null;
        $signup->save();

        return $signup;
    }

    /**
     * Update a signup.
     *
     * @param array $data
     * @param array $data
     * @return \Rogue\Models\Signup
     */
    public function update($signup, $data)
    {
        $signup->update($data);

        return $signup;
    }

    /**
     * Get a signup based on unique fields.
     *
     * @param  string $northstarId
     * @param  int $campaignId
     * @return \Rogue\Models\Signup|null
     */
    public function get($northstarId, $campaignId)
    {
        $signup = Signup::where([
            'northstar_id' => $northstarId,
            'campaign_id' => $campaignId,
        ])->first();

        return $signup;
    }

    /**
     * Delete a signup.
     *
     * @param int $signupId
     * @return $post;
     */
    public function destroy($signupId)
    {
        $signup = Signup::findOrFail($signupId);

        // Soft delete the signup.
        $signup->delete();

        return $signup->trashed();
    }
}
