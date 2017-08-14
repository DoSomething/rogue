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

Route::get('/', function () {
    return view('pages.home');
})->middleware('guest');

Route::group(['middleware' => 'web'], function () {
    // Authentication
    Route::get('login', 'Auth\AuthController@getLogin');
    Route::get('logout', 'Auth\AuthController@getLogout');

    // Campaigns
    Route::get('campaigns', 'CampaignsController@index');
    Route::get('campaigns/{id}/inbox', 'CampaignsController@showInbox');
    Route::get('campaigns/{id}', 'CampaignsController@showCampaign');

    // Users
    Route::get('users', 'UsersController@index');

    // Posts
    Route::post('posts', 'PostController@store');
    Route::get('posts', 'PostController@index');
    Route::delete('posts/{id}', 'PostController@destroy');

    // Reviews
    Route::put('reviews', 'ReviewsController@reviews');

    // Tags
    Route::post('tags', 'TagsController@store');

    // Images
    Route::get('/images/{post}', 'ImagesController@show');
});

// Legacy API Routes
Route::group(['prefix' => 'api/v1', 'middleware' => ['auth.api', 'log.received.request']], function () {
    Route::get('/', function () {
        return 'Rogue API version 1';
    });

    // items
    Route::put('items', 'Api\ReportbackController@updateReportbackItems');

    // reportbacks
    Route::get('reportbacks', 'Api\ReportbackController@index');
    Route::post('reportbacks', 'Api\ReportbackController@store');
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
});
