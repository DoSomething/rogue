<?php

namespace Rogue\Services;

use DoSomething\Gateway\Common\RestApiClient;

class Fastly extends RestApiClient
{
    /**
     * Create a new Fastly API client.
     */
    public function __construct()
    {
        $url = config('services.fastly.url');

        $options = [
            'headers' => [
                'Fastly-Key' => config('services.fastly.key'),
                'Accept'     => 'application/json',
            ],
        ];

        parent::__construct($url, $options);
    }

    /**
     * Purge object from Fastly cache based on give cache key
     *
     * @param $cacheKey String
     */
    public function purgeKey($cacheKey)
    {
        $service = config('services.fastly.service_id');

        return $this->post('service/'.$service.'/purge/'.$cacheKey);
    }
}
