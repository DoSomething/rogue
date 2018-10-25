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
$router->get('campaigns', 'Legacy\Web\CampaignsController@index');
$router->get('campaigns/{id}/inbox', 'Legacy\Web\CampaignsController@showInbox');
$router->get('campaigns/{id}', 'Legacy\Web\CampaignsController@showCampaign')->name('campaigns.show');
// Create, update, delete campaigns via Rogue.
$router->post('campaigns', 'CampaignsController@store');
$router->patch('campaigns/{campaign}', 'CampaignsController@update');
$router->delete('campaigns/{campaign}', 'CampaignsController@destroy');

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
