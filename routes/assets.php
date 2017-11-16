<?php

/**
 * Here is where you can register "raw" routes for your application. These
 * routes are loaded by the RouteServiceProvider with no middleware applied.
 * This is useful for static assets that don't require sessions or auth.
 * Now create something great!
 *
 * @var \Illuminate\Routing\Router $router
 * @see \Rogue\Providers\RouteServiceProvider
 */

// Glide image assets
$router->get('images/{post}', 'ImagesController@show');
