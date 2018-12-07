<?php

/**
 * Here is where you can register web routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * contains the "web" middleware group. Now create something great!
 *
 * @var \Illuminate\Routing\Router $router
 * @see \Rogue\Providers\RouteServiceProvider
 */

// Homepage & FAQ
$router->view('/', 'pages.home')->middleware('guest')->name('login');
$router->view('faq', 'pages.faq');

// Authentication
$router->get('login', 'Auth\AuthController@getLogin');
$router->get('logout', 'Auth\AuthController@getLogout');

// Campaigns
$router->get('campaigns', 'Legacy\Web\CampaignsController@index');
$router->get('campaigns/{id}', 'Legacy\Web\CampaignsController@showCampaign')->name('campaigns.show');

// Create, update, delete campaigns via Rogue.
// @TODO: Merge into CampaignsController, above.
$router->resource('campaign-ids', 'Legacy\Web\CampaignIdsController');

// Inbox
$router->get('campaigns/{id}/inbox', 'Legacy\Web\InboxController@show');

// Users
$router->get('users', ['as' => 'users.index', 'uses' => 'Legacy\Web\UsersController@index']);
$router->get('users/{id}', ['as' => 'users.show', 'uses' => 'Legacy\Web\UsersController@show']);
$router->get('search', ['as' => 'users.search', 'uses' => 'Legacy\Web\UsersController@search']);

// Images
$router->post('images/{postId}', 'Legacy\Web\ImagesController@update');
