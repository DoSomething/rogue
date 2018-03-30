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
$router->get('campaigns', 'Web\CampaignsController@index');
$router->get('campaigns/{id}/inbox', 'Web\CampaignsController@showInbox');
$router->get('campaigns/{id}', 'Web\CampaignsController@showCampaign')->name('campaigns.show');

// Exports
$router->get('exports/{id}', 'Web\ExportController@show');

// Posts
$router->post('posts', 'Web\PostController@store');
$router->get('posts', 'Web\PostController@index');
$router->delete('posts/{id}', 'Web\PostController@destroy');

// Reviews
$router->put('reviews', 'Web\ReviewsController@reviews');

// Signups
$router->get('signups/{id}', 'Web\SignupsController@show')->name('signups.show');

// Tags
$router->post('tags', 'Web\TagsController@store');

// Images
$router->post('images/{postId}', 'Web\ImagesController@update');

// Users
$router->get('users', ['as' => 'users.index', 'uses' => 'Web\UsersController@index']);
$router->get('users/{id}', ['as' => 'users.show', 'uses' => 'Web\UsersController@show']);
$router->get('search', ['as' => 'users.search', 'uses' => 'Web\UsersController@search']);

// FAQ
$router->get('faq', 'Web\PagesController@faq');

// Imports
$router->get('import', 'Web\ImportController@show')->name('import.show');
$router->post('import', 'Web\ImportController@store')->name('import.store');
