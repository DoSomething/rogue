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

// Create, update, delete campaigns via Rogue.
// @TODO: Merge into CampaignsController, above.
$router->resource('campaign-ids', 'CampaignIdsController', ['except' => ['index']]);
$router->get('campaign-ids/{id}/actions/create', 'ActionsController@create');
$router->redirect('campaign-ids', 'campaigns/');

// Actions
$router->resource('actions', 'ActionsController');

// Client-side routes:
Route::middleware(['auth', 'role:staff,admin'])->group(function () {
    // Campaigns
    Route::view('campaigns', 'app')->name('campaigns.index');
    Route::view('campaigns/{id}', 'app');
    Route::view('campaigns/{id}/{status}', 'app');

    // Users
    Route::view('users', 'app')->name('users.index');
    Route::view('users/{id}', 'app')->name('users.show');

    // Posts
    Route::view('posts/{id}', 'app')->name('posts.show');

    // Schools
    Route::view('schools/{id}', 'app')->name('schools.show');

    // Signups
    Route::view('signups/{id}', 'app')->name('signups.show');
});

// Images
$router->post('images/{postId}', 'ImagesController@update');
$router->get('originals/{post}', 'OriginalsController@show');
