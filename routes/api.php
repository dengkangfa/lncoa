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
    Route::get('/user', 'UserController@me');
    Route::post('/user', 'UserController@store');
    Route::get('/users', 'UserController@index');
    Route::put('/user/changepwd', 'UserController@changePassword');
    Route::put('/user/{user}', 'UserController@update');
    Route::delete('/user/{user}', 'UserController@destroy');
    Route::get('/user/{user}/edit', 'UserController@edit');
    Route::post('/user/{id}/status', 'UserController@status');
    Route::post('/user/avatar', 'UserController@avatar');
    Route::post('/user/crop/avatar', 'UserController@cropAvatar');

    Route::get('/menu', 'MenuController@me');
    Route::get('/menus', 'MenuController@index');

    Route::resource('role', 'RoleController', ['except' => ['create', 'show']]);

    Route::get('upload', 'UploadController@index');
    Route::post('upload', 'UploadController@uploadFile');
    Route::post('upload/path', 'UploadController@uploadFileByPath');
    Route::post('folder', 'UploadController@createFolder');
    Route::post('folder/delete', 'UploadController@deleteFolder');
    Route::post('file/delete', 'UploadController@deleteFile');

    Route::resource('permission', 'PermissionController', ['except' => ['create', 'show']]);
    Route::get('system', 'SystemController@getSystemInfo');
});
