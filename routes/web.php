<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('text', function () {
  return \App\Applicat::
              whereIn('type_id',[11,14,15,16,18])
              ->where(function ($query){
                \Log::info($this->id);
                  $query->where('stage',1);
              })
                ->with('user', 'mechanism', 'type', 'status', 'opinions')
                  ->orderBy('created_at', 'desc')->paginate(10);
});
// Route::get('/users', 'HomeController@index')->middleware('menu:/users');
// Route::get('/users', ['middleware' => ['menu:/users'], 'uses' => 'HomeController@index']);
// Route::get('/roles', function () {
//   return 1;
//   return \Auth::user();
//           return  \Log::info(\Auth::user());
// });

Route::get('signup/confirm/{token}', 'UserController@confirmEmail')->name('confirm_email');
   Route::get('{path?}', 'HomeController@index')->where('path', '[\/\w\.-]*');
   Route::get('/', 'HomeController@index');

Route::get('/login', function(){
    return view('index');
});
