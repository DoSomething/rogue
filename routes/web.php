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
$router->get('/', 'PagesController@home')->name('login');

// Authentication
$router->get('login', 'Auth\AuthController@getLogin');
$router->get('logout', 'Auth\AuthController@getLogout');

// Campaigns
$router->get('campaigns', 'CampaignsController@index');
$router->get('campaigns/{id}/inbox', 'CampaignsController@showInbox');
$router->get('campaigns/{id}', 'CampaignsController@showCampaign')->name('campaigns.show');

// Exports
$router->get('exports/{id}', 'ExportController@show');

// Posts
$router->post('posts', 'PostController@store');
$router->get('posts', 'PostController@index');
$router->delete('posts/{id}', 'PostController@destroy');

// Reviews
$router->put('reviews', 'ReviewsController@reviews');

// Signups
$router->get('signups/{id}', 'SignupsController@show')->name('signups.show');

// Tags
$router->post('tags', 'TagsController@store');

// Images
$router->post('images/{postId}', 'ImagesController@update');

// Users
$router->get('users', ['as' => 'users.index', 'uses' => 'UsersController@index']);
$router->get('users/{id}', ['as' => 'users.show', 'uses' => 'UsersController@show']);
$router->get('search', ['as' => 'users.search', 'uses' => 'UsersController@search']);

// FAQ
$router->get('faq', 'PagesController@faq');

// Imports
$router->get('import', 'ImportController@show');
$router->post('import', 'ImportController@store');