<?php

namespace Rogue\Services;

use DoSomething\Gateway\Common\RestApiClient;

class Phoenix extends RestApiClient
{
    /**
     * Create a new Phoenix API client.
     */
    public function __construct()
    {
        $url = config('services.phoenix.uri') . '/api/' . config('services.phoenix.version') . '/';

        parent::__construct($url);
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
