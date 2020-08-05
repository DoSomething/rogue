<?php

/**
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * contains the "api" middleware group. Now create something great!
 *
 * @var \Illuminate\Routing\Router $router
 * @see \Rogue\Providers\RouteServiceProvider
 */

// Assets
Route::get('images/{hash}', 'Web\ImagesController@show');

// v3 routes
Route::group(['prefix' => 'api/v3', 'middleware' => ['guard:api']], function () {
    // signups
    Route::post('signups', 'SignupsController@store');
    Route::get('signups', 'SignupsController@index');
    Route::get('signups/{signup}', 'SignupsController@show');
    Route::patch('signups/{signup}', 'SignupsController@update');
    Route::delete('signups/{signup}', 'SignupsController@destroy');

    // posts
    Route::post('posts', 'PostsController@store');
    Route::get('posts', 'PostsController@index');
    Route::get('posts/{post}', 'PostsController@show');
    Route::patch('posts/{post}', 'PostsController@update');
    Route::delete('posts/{post}', 'PostsController@destroy');

    // reactions
    Route::post('posts/{post}/reactions', 'ReactionController@store');
    Route::get('posts/{post}/reactions', 'ReactionController@index');

    // reviews
    Route::post('posts/{post}/reviews', 'ReviewsController@reviews');

    // tag
    Route::post('posts/{post}/tags', 'TagsController@store');

    // images
    Route::post('posts/{post}/rotate', 'RotationController@update');

    // events
    Route::get('events', 'EventsController@index');

    // campaigns
    Route::get('campaigns', 'CampaignsController@index');
    Route::get('campaigns/{campaign}', 'CampaignsController@show');
    Route::patch('campaigns/{campaign}', 'CampaignsController@update');

    // actions
    Route::get('actions', 'ActionsController@index');
    Route::get('actions/{action}', 'ActionsController@show');

    // action stats
    Route::get('action-stats', 'ActionStatsController@index');

    // groups
    Route::get('groups', 'GroupsController@index');
    Route::get('groups/{group}', 'GroupsController@show');

    // group types
    Route::get('group-types', 'GroupTypesController@index');
    Route::get('group-types/{groupType}', 'GroupTypesController@show');

    // users
    Route::delete('users/{id}', 'UsersController@destroy');
});
