<?php

namespace Rogue\Services;

class CustomerIo
{
    /**
     * The Customer.io client.
     *
     * @var Client
     */
    protected $client;

    /**
     * Create a new Customer.io API client.
     */
    public function __construct()
    {
        $config = config('services.customerio');

        $this->client = new \GuzzleHttp\Client([
            'base_uri' => $config['url'],
            'auth' => [$config['username'], $config['password']],
        ]);
    }

    /**
     * Is Customer.io enabled for this app?
     *
     * @return bool
     */
    protected function enabled(): bool
    {
        return config('features.customer_io');
    }

    /**
     * Track Customer.io event for given user with given name and data.
     * @see https://customer.io/docs/api/#apitrackeventsevent_add
     *
     * @param string $userId
     * @param string $eventName
     * @param array $eventData
     */
    public function trackEvent(string $userId, $eventName, $eventData = [])
    {
        if (!$this->enabled()) {
            info('Event would have been sent to Customer.io', [
                'id' => $userId,
                'name' => $eventName,
                'data' => $eventData,
            ]);

            return;
        }

        $response = $this->client->post('customers/' . $userId . '/events', [
            'json' => ['name' => $eventName, 'data' => $eventData],
        ]);

        // For this endpoint, any status besides 200 means something is wrong:
        if ($response->getStatusCode() !== 200) {
            throw new Exception(
                'Customer.io error: ' . (string) $response->getBody(),
            );
        }

        info('Event sent to Customer.io', [
            'id' => $userId,
            'name' => $eventName,
        ]);
    }
}
