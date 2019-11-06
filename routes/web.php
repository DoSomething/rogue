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
$router->get('login', 'AuthController@getLogin');
$router->get('logout', 'AuthController@getLogout');

// Campaigns
$router->get('campaigns/{id}', 'CampaignsController@show')->name('campaigns.show');

// Create, update, delete campaigns via Rogue.
// @TODO: Merge into CampaignsController, above.
$router->resource('campaign-ids', 'CampaignIdsController');
$router->get('campaign-ids/{id}/actions/create', 'ActionsController@create');

// Actions
$router->resource('actions', 'ActionsController');

// Inbox
$router->get('campaigns/{id}/inbox', 'InboxController@show');

// Signups
$router->get('signups/{id}', 'SignupsController@show')->name('signups.show');

// Client-side routes:
Route::middleware(['auth', 'role:staff,admin'])->group(function () {
    // Campaigns
    Route::view('campaigns', 'app')->name('campaigns.index');

    // Users
    Route::view('users', 'app')->name('users.index');
    Route::view('users/{id}', 'app')->name('users.show');

    // Posts
    Route::view('posts/{id}', 'app')->name('posts.show');
});

// Images
$router->post('images/{postId}', 'ImagesController@update');
$router->get('originals/{post}', 'OriginalsController@show');
