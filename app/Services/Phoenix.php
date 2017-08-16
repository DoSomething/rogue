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
     * @param  array $params
     * @return array|null
     */
    public function getAllCampaigns($params = [])
    {
        return $this->get('campaigns', $params);
    }

    /**
     * Send a GET request to return a campaign with the specified id.
     *
     * @param  string $id
     * @return array|null
     */
    public function getCampaign($id)
    {
        return $this->get('campaigns/' . $id);
    }
}
