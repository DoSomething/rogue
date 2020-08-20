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
   * Track Customer.io event for given user with given name and data.
   * @see https://customer.io/docs/api/#apitrackeventsevent_add
   *
   * @param string $userId
   * @param string $eventName
   * @param array $eventData
   */
  public function trackEvent($userId, $eventName, $eventData = [])
  {
    $payload = ['name' => $eventName];

    foreach ($eventData as $key => $value) {
      $payload["data[$key]"] = $value;
    }

    return $this->client->post('customers/' . $userId . '/events', [
      'form_params' => $payload,
    ]);
  }
}
