<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['middleware' => 'web'], function () {
    Route::get('/', function () {
        return view('pages.home');
    })->middleware('guest');

    // Authentication
    Route::get('login', 'Auth\AuthController@getLogin');
    Route::get('logout', 'Auth\AuthController@getLogout');

    // Campaigns
    Route::get('campaigns', 'CampaignsController@index');
    Route::get('campaigns/{id}/inbox', 'CampaignsController@showInbox');
    Route::get('campaigns/{id}', 'CampaignsController@showCampaign');

    // Posts
    Route::post('posts', 'PostController@store');
    Route::get('posts', 'PostController@index');
    Route::delete('posts/{id}', 'PostController@destroy');

    // Reviews
    Route::put('reviews', 'ReviewsController@reviews');

    // Signups
    Route::get('signups/{id}', 'SignupsController@show');

    // Tags
    Route::post('tags', 'TagsController@store');

    // Images
    Route::get('/images/{post}', 'ImagesController@show');
    Route::post('images/{postId}', 'ImagesController@update');

    // Users
    Route::get('users', ['as' => 'users.index', 'uses' => 'UsersController@index']);
    Route::get('users/{id}', ['as' => 'users.show', 'uses' => 'UsersController@show']);
    Route::get('search', ['as' => 'users.search', 'uses' => 'UsersController@search']);
});

// Legacy API Routes
Route::group(['prefix' => 'api/v1', 'middleware' => ['auth.api', 'log.received.request']], function () {
    Route::get('/', function () {
        return 'Rogue API version 1';
    });

    // reportbacks
    Route::get('reportbacks', 'Api\ReportbackController@index');
});

// v2 routes
Route::group(['prefix' => 'api/v2', 'middleware' => ['auth.api', 'log.received.request']], function () {

    // activity
    Route::get('activity', 'Api\ActivityController@index');

    // posts
    Route::post('posts', 'Api\PostsController@store');
    Route::get('posts', 'Api\PostsController@index');

    // reactions
    Route::post('reactions', 'Api\ReactionController@store');

    // reviews
    Route::post('reviews', 'Api\ReviewsController@reviews');

    // signups
    Route::post('signups', 'Api\SignupsController@store');

    // tags
    Route::post('tags', 'Api\TagsController@store');

    // Campaigns
    Route::get('campaigns', 'Api\CampaignsController@index');
});
