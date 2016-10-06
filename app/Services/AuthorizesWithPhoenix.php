<?php

namespace Rogue\Services;

use GuzzleHttp\Cookie\CookieJar;

trait AuthorizesWithPhoenix
{
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
     * Run custom tasks before making a request.
     *
     * @see RestApiClient@raw
     */
    public function runAuthorizesWithPhoenixTasks($method, &$path, &$options, &$withAuthorization)
    {
        if ($withAuthorization) {
            if (! isset($options['token'])) {
                $authorizationHeader = [];
                $authorizationHeader['X-CSRF-Token'] = $this->getAuthenticationToken();
                $options['cookies'] = $this->getAuthenticationCookie();
                $options['headers'] = array_merge($this->defaultHeaders, $authorizationHeader);
            }
        }
    }
}
