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

Route::get('/', function () {
    return view('auth/login');
});
Route::post('login', 'Auth\LoginController@login')->name('login');
Route::post('login_a', 'Auth\LoginController@login_admin')->name('login_admin');
Route::get('/index', 'Auth\LoginController@index');
Route::post('/index', function(){
    return view('index');
});
Route::get('/auth', 'Auth\LoginController@auth');
Route::get('/back', 'Auth\LoginController@back')->name('back');
Route::get('/clockIn', 'Controller@clockIn');
Route::get('/clockOut', 'Controller@clockOut');
Route::get('/logOut', 'Auth\LoginController@logout');
Route::get('/home', 'Auth\LoginController@returnAdmin');
Route::post('/coach','Controller@view')->name('coach'); 
Route::get('/coach',function(){
    return view('coach');
});
Route::get('/change/{day}', 'Controller@day_changed');
Route::post('/update', 'Controller@update');
Route::post('/addTask', 'Controller@addTask'); 
Route::post('/coach-edit', 'Controller@view');
Route::post('/delTask', 'Controller@delTask');
Route::get('/coaches', 'Controller@manage_Coaches');
Route::post('/add_coach', 'Controller@addCoach');
Route::post('/add_schedule', 'Controller@addSchedule');
