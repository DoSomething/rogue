<?php

/**
 * Here is where you can register web routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * contains the "web" middleware group. Now create something great!
 *
 * @var \Illuminate\Routing\Router $router
 * @see \Rogue\Providers\RouteServiceProvider
 */

// Homepage
$router->get('/', 'Legacy\Web\PagesController@home')->name('login');

// Authentication
$router->get('login', 'Auth\AuthController@getLogin');
$router->get('logout', 'Auth\AuthController@getLogout');

// Campaigns
$router->get('campaigns', 'Legacy\Web\AshesCampaignsController@index');
$router->get('campaigns/{id}/inbox', 'Legacy\Web\AshesCampaignsController@showInbox');
$router->get('campaigns/{id}', 'Legacy\Web\AshesCampaignsController@showCampaign')->name('campaigns.show');
// Create, update, delete campaigns via Rogue.
// @TODO: rename these routes (to just /campaigns) when we get off Ashes.
$router->get('campaign-ids/create', ['as' => 'campaign_id.create', 'uses' => 'Legacy\Web\CampaignsController@create']);
$router->get('campaign-ids/{campaign}', ['as' => 'campaign_id.show', 'uses' => 'Legacy\Web\CampaignsController@show']);
$router->post('campaign-ids', ['as' => 'campaign_id.store', 'uses' => 'Legacy\Web\CampaignsController@store']);
$router->patch('campaign-ids/{campaign}',['as' => 'campaign_id.update', 'uses' => 'Legacy\Web\CampaignsController@update']);
$router->get('campaign-ids/{campaign}/edit', ['as' => 'campaign_id.edit', 'uses' => 'Legacy\Web\CampaignsController@edit']);
$router->delete('campaign-ids/{campaign}', 'Legacy\Web\CampaignsController@destroy');
$router->get('campaign-ids', 'Legacy\Web\CampaignsController@index');

// Exports
$router->get('exports/{id}', 'Web\ExportController@show');

// Posts
$router->post('posts', 'Legacy\Web\PostController@store');
$router->get('posts', 'Legacy\Web\PostController@index');
$router->delete('posts/{id}', 'Legacy\Web\PostController@destroy');

// Reviews
$router->put('reviews', 'Legacy\Web\ReviewsController@reviews');

// Signups
$router->get('signups/{id}', 'Legacy\Web\SignupsController@show')->name('signups.show');

// Tags
$router->post('tags', 'Legacy\Web\TagsController@store');

// Images
$router->post('images/{postId}', 'Legacy\Web\ImagesController@update');

// Users
$router->get('users', ['as' => 'users.index', 'uses' => 'Legacy\Web\UsersController@index']);
$router->get('users/{id}', ['as' => 'users.show', 'uses' => 'Legacy\Web\UsersController@show']);
$router->get('search', ['as' => 'users.search', 'uses' => 'Legacy\Web\UsersController@search']);

// FAQ
$router->get('faq', 'Legacy\Web\PagesController@faq');

// Imports
$router->get('import', 'Legacy\Web\ImportController@show')->name('import.show');
$router->post('import', 'Legacy\Web\ImportController@store')->name('import.store');
