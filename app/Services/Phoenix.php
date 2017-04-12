<?php

namespace Rogue\Services;

use DoSomething\Gateway\Common\RestApiClient;
use DoSomething\Gateway\ForwardsTransactionIds;

class Phoenix extends RestApiClient
{
    use AuthorizesWithPhoenix, ForwardsTransactionIds;

    /**
     * The class name of the transaction framework bridge.
     *
     * @var string
     */
    protected $transactionBridge = \DoSomething\Gateway\Laravel\LaravelTransactionBridge::class;

    /**
     * Create a new Phoenix API client.
     */
    public function __construct()
    {
        $url = config('services.phoenix.uri') . '/api/' . config('services.phoenix.version') . '/';

        parent::__construct($url);
    }

    /**
     * Send a POST request to save a copy of the reportback in Phoenix.
     *
     * @param array $body
     * @param bool $withAuthorization - Should this request be authorized?
     * @return object|false
     */
    public function postReportback($body = [])
    {
        $response = $this->post('campaigns/' . $body['nid'] . '/reportback', $body);

        return is_null($response) ? null : $response;
    }

    /**
     * Send a POST request to save a copy of the signup in Phoenix.
     *
     * @param array $body
     * @return object|false
     */
    public function postSignup($body, $campaign_id)
    {
        $response = $this->post('campaigns/' . $campaign_id . '/signup', $body);

        return $response;
    }

    /**
     * Send a GET request to return all campaigns matching a given query.
     *
     * @param  array  $params
     * @return object|null
     */
    public function getAllCampaigns($params = [])
    {
        $response = $this->get('campaigns', $params);

        return is_null($response) ? null : $response;
    }

    /**
     * Send a GET request to return a campaign with the specified id.
     *
     * @param  string $id
     * @return object|null
     */
    public function getCampaign($id)
    {
        $response = $this->get('campaigns/' . $id);

        return is_null($response) ? null : $response;
    }
}
