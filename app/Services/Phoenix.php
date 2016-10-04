<?php

namespace Rogue\Services;

use DoSomething\Gateway\Common\RestApiClient;
use DoSomething\Gateway\ForwardsTransactionIds;
use GuzzleHttp\Cookie\CookieJar;
use Cache;

class Phoenix extends RestApiClient
{
    use AuthorizesWithPhoenix, ForwardsTransactionIds;

    /**
     * Create a new Phoenix API client.
     */
    public function __construct()
    {
        $url = config('services.phoenix.uri') . '/api/' . config('services.phoenix.version') . '/';

        parent::__construct($url);
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

            $response = $this->post('auth/login', $payload, false);
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
        $response = $this->post('campaigns/' . $nid . '/reportback', $body);

        return is_null($response) ? null : $response;
    }
}
