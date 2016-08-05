<?php

namespace Rogue\Services\Phoenix;

use DoSomething\Northstar\Common\RestApiClient;
use DoSomething\Northstar\Resources\NorthstarClient;
use DoSomething\Northstar\Resources\NorthstarUser;
use DoSomething\Northstar\Resources\NorthstarUserCollection;
use DoSomething\Northstar\Resources\NorthstarClientCollection;
use GuzzleHttp\Cookie\CookieJar;
use Cache;


class Phoenix extends RestApiClient
{
	/**
	* Create a new Phoenix API client.
	* @param array $config
	*/
	public function __construct()
	{
        $this->base_uri = config('services.phoenix.uri') . '/api/' . config('services.phoenix.version') . '/';

        $headers = [
           'Content-type' => 'application/json',
           'Accept' => 'application/json',
        ];

        parent::__construct($this->base_uri, $headers);
	}

	/**
	* Returns a token for making authenticated requests to the Drupal API.
	*
	* @return array - Cookie & token for authenticated requests
	*/
	private function authenticate()
	{
		$authentication = Cache::remember('drupal.authentication', 30, function() {
			$payload = [
				'username' => env('PHOENIX_USERNAME'),
				'password' => env('PHOENIX_PASSWORD'),
			];


			$response = $this->post($this->base_uri . 'auth/login', $payload, false);

			$body = json_decode($response->getBody()->getContents(), true);
			$session_name = $body['session_name'];
			$session_value = $body['sessid'];

			return [
				'cookie' => [$session_name => $session_value],
				'token' => $body['token'],
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
	* Send a POST request to also save a copy of the reportback in Phoenix.
	*
	* @param string $nid
	* @param array $body
	* @param bool $withAuthorization - Should this request be authorized?
	* @return object|false
	*/
	public function postReportback($nid, $body = [])
	{
		$response = $this->post($this->base_uri . 'campaigns/' . $nid . '/reportback', $body, $withAuthorization = true);

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
	function raw($method, $path, $options, $withAuthorization = true)
	{
		if ($withAuthorization) {
			if (!isset($options['token'])) {
				$authorizationHeader = [];
				$authorizationHeader['X-CSRF-Token'] = $this->getAuthenticationToken();
				$authorizationHeader['Cookie']  = $this->getAuthenticationCookie();
				$options['headers'] = array_merge($this->defaultHeaders, $options['headers'], $authorizationHeader);
			}
		}
		// do we want to use client here?
        return $this->client->request($method, $path, $options);
	}
}