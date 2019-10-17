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
$router->get('images/{hash}', 'Web\ImagesController@show');

// v3 routes
$router->group(['prefix' => 'api/v3', 'middleware' => ['guard:api']], function () {
    // signups
    $this->post('signups', 'SignupsController@store');
    $this->get('signups', 'SignupsController@index');
    $this->get('signups/{signup}', 'SignupsController@show');
    $this->patch('signups/{signup}', 'SignupsController@update');
    $this->delete('signups/{signup}', 'SignupsController@destroy');

    // posts
    $this->post('posts', 'PostsController@store');
    $this->get('posts', 'PostsController@index');
    $this->get('posts/{post}', 'PostsController@show');
    $this->patch('posts/{post}', 'PostsController@update');
    $this->delete('posts/{post}', 'PostsController@destroy');

    // reactions
    $this->post('posts/{post}/reactions', 'ReactionController@store');
    $this->get('posts/{post}/reactions', 'ReactionController@index');

    // reviews
    $this->post('posts/{post}/reviews', 'ReviewsController@reviews');

    // tag
    $this->post('posts/{post}/tags', 'TagsController@store');

    // events
    $this->get('events', 'EventsController@index');

    // campaigns
    $this->get('campaigns', 'CampaignsController@index');
    $this->get('campaigns/{campaign}', 'CampaignsController@show');

    // actions
    $this->get('actions', 'ActionsController@index');
    $this->get('actions/{action}', 'ActionsController@show');
});
