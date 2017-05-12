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
    Route::get('statistics', 'IndexController@statistics');
    Route::get('weather', 'IndexController@weather');
    Route::get('loginlogs', 'IndexController@loginlogs');
    // Route::resource('user', 'UserController', ['except' => ['create', 'show']]);
    Route::get('/user', 'UserController@me');
    Route::post('/user', ['middleware' => ['permission:create-user'], 'uses' => 'UserController@store']);
    Route::get('/users', 'UserController@index');
    Route::put('/user/changepwd', 'UserController@changePassword');
    Route::put('/user/{user}', ['middleware' => ['permission:update-user'], 'uses' => 'UserController@update']);
    Route::delete('/user/{user}',['middleware' => ['permission:delete-user'], 'uses' => 'UserController@destroy']);
    Route::get('/user/{user}/edit', 'UserController@edit');
    Route::post('/user/{id}/status', ['middleware' => ['permission:update-user-status'], 'uses' => 'UserController@status']);
    Route::post('/user/avatar', 'UserController@avatar');
    Route::post('/user/crop/avatar', 'UserController@cropAvatar');
    Route::get('/user/upload', 'UserController@uploadList');
    Route::get('/user/filesystemsize', 'UserController@userFileStyleSize');
    Route::post('/user/upload', 'UserController@uploadFile');
    Route::post('/user/file/delete', 'UserController@deleteFile');
    Route::post('/user/folder', 'UserController@createFolder');
    Route::post('/user/file/rename', 'UserController@renameFile');

    Route::get('/menu', 'MenuController@me');
    Route::get('/menus', 'MenuController@index');

    Route::resource('role', 'RoleController', ['except' => ['create', 'show']]);
    Route::get('role/{id}/users', 'RoleController@users');

    Route::get('upload', 'UploadController@index');
    Route::post('upload', 'UploadController@uploadFile');
    Route::post('upload/path', 'UploadController@uploadFileByPath');
    Route::post('folder', 'UploadController@createFolder');
    Route::post('folder/delete', 'UploadController@deleteFolder');
    Route::post('file/delete', 'UploadController@deleteFile');

    Route::resource('permission', 'PermissionController', ['except' => ['create', 'show']]);

    Route::get('type/me', 'TypeController@me');
    Route::put('type/sorts', 'TypeController@updateSorts');
    Route::resource('type', 'TypeController');

    Route::post('applicat/file', 'ApplicatController@upload');
    Route::resource('applicat', 'ApplicatController');
    Route::get('applicat', 'ApplicatController@me');
    Route::post('applicat/{id}/forward', 'ApplicatController@forward');
    Route::put('applicat/{id}/approval', 'ApplicatController@approval');
    Route::get('applicat/{type_id}/dateTakeUp', 'ApplicatController@dateTakeUp');
    Route::get('applicats', 'ApplicatController@index');
    Route::delete('applicat/{id}', 'ApplicatController@destroy');
    Route::delete('applicat/{id}/softdelete', 'ApplicatController@SoftDeletes');
    Route::put('applicat/{id}/cancel', 'ApplicatController@cancel');

    Route::resource('opinion', 'OpinionController');

    Route::get('system', 'SystemController@getSystemInfo');

    Route::get('notice', 'NoticeController@index');
    Route::post('notice', 'NoticeController@store');
    Route::put('notice/enabled', 'NoticeController@enabled');

    Route::get('notificat', 'NotificatController@index');
    Route::delete('notificat/{id}', 'NotificatController@destroy');

    Route::get('errorlog', 'LogController@errorLog');
});

Route::group([
    'namespace' => 'Api',
], function () {
    Route::post('login', 'LoginController@login');
    Route::get('register/{rule}/check', 'RegisterController@check');
    Route::post('register', 'RegisterController@register');
    Route::get('mechanism', 'MechanismController@index');
    Route::get('signup/confirm/{token}', 'UserController@confirmEmail')->name('confirm_email');
});
Route::post('password/email','Auth\ForgotPasswordController@sendResetLinkEmail');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');
