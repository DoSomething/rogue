<?php

namespace Tests\Http;

use Tests\TestCase;
use Rogue\Models\Action;
use Rogue\Models\Campaign;


class ActionTest extends TestCase
{
    /**
     * Test that a POST request to /actions creates a new action.
     *
     * POST /actions
     * @return void
     */
    public function testCreatingAnAction()
    {
        // Create a campaign that the action will belong to.
        $campaign = factory(Campaign::class)->create();
        $actionName = $this->faker->sentence;

        // Create an action;
        $this->actingAsAdmin()->postJson('actions', [
            'name' => $actionName,
            'campaign_id' => $campaign->id,
            'post_type' => 'photo',
            'reportback' => 1,
            'civic_action' => 0,
            'scholarship_entry' => 1,
            'noun' => 'things',
            'verb' => 'done',
        ]);

        // Make sure the action is persisted.
        $this->assertDatabaseHas('actions', [
            'name' => $actionName,
        ]);
    }

    /**
     * Test that a GET request to /api/v3/actions returns an index of all campaigns.
     *
     * GET /api/v3/actions
     * @return void
     */
    public function testActionIndex()
    {
        // Create a campaign that the actions will belong to.
        $campaign = factory(Campaign::class)->create();

        // Create five actions.
        factory(Action::class, 5)->create(['campaign_id' => $campaign->id]);

        $response = $this->getJson('api/v3/actions');
        $decodedResponse = $response->decodeResponseJson();

        $response->assertStatus(200);
        $this->assertEquals(5, $decodedResponse['meta']['pagination']['count']);
    }

    /**
     * Test that a GET request to /api/v3/actions/:action_id returns the intended action.
     *
     * GET /api/v3/actions/:action_id
     * @return void
     */
    public function testActionShow()
    {
        // Create a campaign that the action will belong to.
        $campaign = factory(Campaign::class)->create();

        // Create five actions.
        factory(Action::class, 5)->create(['campaign_id' => $campaign->id]);

        // Create 1 specific action to search for.
        $secondCampaign = factory(Campaign::class)->create();

        $action = factory(Action::class)->create(['campaign_id' => $secondCampaign->id]);

        $response = $this->getJson('api/v3/actions/' . $action->id);
        $decodedResponse = $response->decodeResponseJson();

        $response->assertStatus(200);
        $this->assertEquals($action->id, $decodedResponse['data']['id']);
    }

    /**
     * Test that a PATCH request to /actions/:action_id updates an action.
     *
     * PATCH /actions/:action_id
     * @return void
     */
    public function testUpdatingAnAction()
    {
        // Create a campaign that the action will belong to.
        $campaign = factory(Campaign::class)->create();

        // Create an action.
        $action = factory(Action::class)->create(['campaign_id' => $campaign->id]);

        // Update the name.
        $this->actingAsAdmin()->patchJson('actions/' . $action->id, [
            'name' => 'Updated Name',
        ]);

        // Make sure the action update is persisted.
        $response = $this->getJson('api/v3/actions/' . $action->id);
        $decodedResponse = $response->decodeResponseJson();

        $response->assertStatus(200);
        $this->assertEquals('Updated Name', $decodedResponse['data']['name']);
    }

    /**
     * Test that a DELETE request to /actions/:action_id deletes an action.
     *
     * DELETE /actions/:action_id
     * @return void
     */
    public function testDeleteAnAction()
    {
        // Create a campaign that the action will belong to.
        $campaign = factory(Campaign::class)->create();

        // Create an action.
        $action = factory(Action::class)->create(['campaign_id' => $campaign->id]);

        // Delete the action.
        $this->actingAsAdmin()->deleteJson('actions/' . $action->id);

        // Make sure the action is deleted.
        $response = $this->getJson('api/v3/actions/' . $action->id);
        $decodedResponse = $response->decodeResponseJson();

        $response->assertStatus(404);
        $this->assertEquals('That resource could not be found.', $decodedResponse['message']);
    }
}
