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
    return view('welcome');
});

Route::get('/admin/login', function () {
    return view('admin/login');
});
 
// Authentication routes...
Route::get('/auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

Route::get('admin/dashboard', 'DashboardController@index');

# Admin Panel Routes
Route::post('admin/store', 'RegistrationController@store');
Route::get('admin/userList', 'RegistrationController@index');

Route::get('admin/users/{id}/delete', 'RegistrationController@destroy');
Route::resource('admin/users', 'RegistrationController');

