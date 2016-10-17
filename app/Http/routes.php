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

// Authentication
Route::get('login', 'Auth\AuthController@getLogin');
Route::get('logout', 'Auth\AuthController@getLogout');

Route::get('/', function () {
    return view('pages.home');
});

Route::get('/test', function () {
    return session('oauth_state');
});


// API Routes
Route::group(['prefix' => 'api/v1', 'middleware' => ['api']], function () {
    Route::get('/', function () {
        return 'Rogue API version 1';
    });

    Route::get('reportbacks', 'Api\ReportbackController@index');
    Route::post('reportbacks', 'Api\ReportbackController@store');
    Route::put('items', 'Api\ReportbackController@updateReportbackItems');
    Route::get('users', 'UsersController@index');
});
