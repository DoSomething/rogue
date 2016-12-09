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

    // Default route to send authenticated users to.
    // NOTE: For testing authentication only.
    Route::get('reportbacks', 'ReportbacksController@index');

    Route::get('users', 'UsersController@index');
});

// Legacy API Routes
Route::group(['prefix' => 'api/v1', 'middleware' => ['api', 'log.received.request']], function () {
    Route::get('/', function () {
        return 'Rogue API version 1';
    });

    // /items
    Route::put('items', 'Api\ReportbackController@updateReportbackItems');

    // /reactions
    Route::post('reactions', 'Api\ReactionController@store');

    // /reportbacks
    Route::get('reportbacks', 'Api\ReportbackController@index');
    Route::post('reportbacks', 'Api\ReportbackController@store');
});
