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
$router->get('images/{post}', 'ImagesController@show')->middleware('add-cache-key:image');;

// Legacy API Routes
$router->group(['prefix' => 'api/v1', 'middleware' => ['legacy-auth']], function () {
    // reportbacks
    $this->get('reportbacks', 'Api\ReportbackController@index');
});

// v2 routes
$router->group(['prefix' => 'api/v2', 'middleware' => ['legacy-auth']], function () {

    // activity
    $this->get('activity', 'Api\ActivityController@index');

    // events
    $this->get('events', 'Api\EventController@index');

    // posts
    $this->post('posts', 'Api\PostsController@store');
    $this->get('posts', 'Api\PostsController@index');

    // reactions
    $this->post('reactions', 'Api\ReactionController@store');

    // reviews
    $this->post('reviews', 'Api\ReviewsController@reviews');

    // signups
    $this->post('signups', 'Api\SignupsController@store');

    // tags
    $this->post('tags', 'Api\TagsController@store');

    // Campaigns
    $this->get('campaigns', 'Api\CampaignsController@index');
});

// v3 routes
$router->group(['prefix' => 'api/v3', 'middleware' => ['guard:api']], function () {
    // signups
    $this->post('signups', 'Three\SignupsController@store');
    $this->get('signups', 'Three\SignupsController@index');
    $this->get('signups/{signup}', 'Three\SignupsController@show');
    $this->patch('signups/{signup}', 'Three\SignupsController@update');
    $this->delete('signups/{signup}', 'Three\SignupsController@destroy');

    // posts
    $this->post('posts', 'Three\PostsController@store');
    $this->get('posts', 'Three\PostsController@index');
    $this->get('posts/{post}', 'Three\PostsController@show');
    $this->patch('posts/{post}', 'Three\PostsController@update');
    $this->delete('posts/{post}', 'Three\PostsController@destroy');

    // reactions
    $this->post('post/{post}/reactions', 'Three\ReactionController@store');
    $this->get('post/{post}/reactions', 'Three\ReactionController@index');
});
