<?php

namespace Rogue\Services;

use DoSomething\Gateway\Common\RestApiClient;

class Blink extends RestApiClient
{
    /**
     * Create a new Blink API client.
     */
    public function __construct()
    {
        $url = config('services.blink.url') . '/v1/';

        parent::__construct($url);
    }

    /**
     * Send a POST request to be sent to Quasar.
     *
     * @param  array $params
     * @return array|null
     */
    public function sendToQuasar($request)
    {
        return $this->post('events/quasar-relay', $request);
    }
}
