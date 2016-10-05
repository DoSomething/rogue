<?php

namespace Rogue\Services;

trait AuthorizesWithPhoenix
{
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
