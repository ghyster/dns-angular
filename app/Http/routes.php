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

Route::get('/', 'HomeController@index');

Route::get('/login/google', 'LoginController@google');
Route::post('/login/google', 'LoginController@postGoogle');
Route::post('/login/logout', 'LoginController@logout');

Route::get('/zone', 'ZoneController@getAllZones');
Route::post('/zone/save', 'ZoneController@postSave');
Route::post('/zone/record', 'ZoneController@postRecord');
Route::post('/zone/removerecord', 'ZoneController@postRemoverecord');
Route::get('/zone/get', 'ZoneController@get');

Route::get('/user/all', 'UserController@getAllUsers');
Route::post('/user/save', 'UserController@postSave');
Route::post('/user/remove', 'UserController@postRemove');

/*Route::controllers([
    'zone'	=> 'ZoneController',
    'user'	=> 'UserController',
    'login'	=> 'LoginController'
]);*/
