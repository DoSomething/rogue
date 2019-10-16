<?php

namespace Rogue\Http\Controllers\Web;

use Rogue\Http\Controllers\Controller;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/campaigns';

    /**
     * Where to redirect users after logout.
     *
     * @var string
     */
    protected $redirectAfterLogout = '/';

    /**
     * Handle a login request to the application.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getLogin(ServerRequestInterface $request, ResponseInterface $response)
    {
        // Save the post-login redirect for when the user completes the flow: either to the intended
        // page (if logging in to view a page protected by the 'auth' middleware), or the previous
        // page (if the user clicked "Log In" in the top navigation).
        if (! array_has($request->getQueryParams(), 'code')) {
            $intended = session()->pull('url.intended', url()->previous());
            session(['login.intended' => $intended]);
        }

        $destination = array_get($request->getQueryParams(), 'destination');
        $url = session('login.intended', $this->redirectTo);

        return gateway('northstar')->authorize($request, $response, $url, $destination);
    }

    /**
     * Handle a logout request to the application.
     *
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function getLogout(ResponseInterface $response)
    {
        return gateway('northstar')->logout($response, $this->redirectAfterLogout);
    }
}
