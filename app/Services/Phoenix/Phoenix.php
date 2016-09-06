<?php

namespace Rogue\Services\Phoenix;

use DoSomething\Northstar\Common\RestApiClient;
use GuzzleHttp\Cookie\CookieJar;
use Cache;

class Phoenix extends RestApiClient
{
    /**
     * Create a new Phoenix API client.
     */
    public function __construct()
    {
        $this->url = config('services.phoenix.uri') . '/api/' . config('services.phoenix.version') . '/';

        parent::__construct($this->url);
    }

    /**
     * Returns a token for making authenticated requests to the Drupal API.
     *
     * @return array - Cookie & token for authenticated requests
     */
    private function authenticate()
    {
        $authentication = Cache::remember('drupal.authentication', 30, function () {
            $payload = [
                'username' => config('services.phoenix.username'),
                'password' => config('services.phoenix.password'),
            ];

            $response = $this->post($this->url . 'auth/login', $payload, false);
            // $body = json_decode($response->getBody()->getContents(), true);
            $session_name = $response['session_name'];
            $session_value = $response['sessid'];

            return [
                'cookie' => [$session_name => $session_value],
                'token' => $response['token'],
            ];
        });

        return $authentication;
    }

    /**
     * Get the CSRF token for the authenticated API session.
     *
     * @return string - token
     */
    private function getAuthenticationToken()
    {
        return $this->authenticate()['token'];
    }

    /**
     * Get the cookie for the authenitcated API session.
     *
     * @return CookieJar
     */
    private function getAuthenticationCookie()
    {
        $cookieDomain = parse_url(config('services.phoenix.uri'))['host'];

        return CookieJar::fromArray($this->authenticate()['cookie'], $cookieDomain);
    }

    /**
     * Send a POST request to save a copy of the reportback in Phoenix.
     *
     * @param string $nid
     * @param array $body
     * @param bool $withAuthorization - Should this request be authorized?
     * @return object|false
     */
    public function postReportback($nid, $body = [])
    {
        $response = $this->post($this->url . 'campaigns/' . $nid . '/reportback', $body, $withAuthorization = true);
        return is_null($response) ? null : $response;
    }

    /**
     * Overrides DoSometing\Northstar\Common\RestApiClient to add Cookie and X-CSRF-Token to header.
     *
     * @param $method
     * @param $path
     * @param array $options
     * @param bool $withAuthorization
     * @return response
     */
    public function raw($method, $path, $options, $withAuthorization = true)
    {
        if ($withAuthorization) {
            if (! isset($options['token'])) {
                $authorizationHeader = [];
                $authorizationHeader['X-CSRF-Token'] = $this->getAuthenticationToken();
                $options['cookies'] = $this->getAuthenticationCookie();
                $options['headers'] = array_merge($this->defaultHeaders, $authorizationHeader);
            }
        }

        return $this->client->request($method, $path, $options);
    }
}
