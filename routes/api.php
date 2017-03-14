<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group([
    'middleware' => ['auth:api'],
    'namespace' => 'Api',
], function () {
    // Route::resource('user', 'UserController', ['except' => ['create', 'show']]);
    Route::get('/tree', 'TreeController@me');
    Route::get('/user', 'UserController@me');
    Route::post('/user', 'UserController@store');
    Route::get('/users', 'UserController@index');
    Route::put('/user/{user}', 'UserController@update');
    Route::delete('/user/{user}', 'UserController@destroy');
    Route::get('/user/{user}/edit', 'UserController@edit');
    Route::post('/users/{id}/status', 'UserController@status');
    Route::resource('role', 'RoleController', ['except' => ['create', 'show']]);
    Route::resource('permission', 'PermissionController', ['except' => ['create', 'show']]);
    Route::get('system', 'SystemController@getSystemInfo');
});
