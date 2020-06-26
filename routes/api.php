<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/user', 'UserController@register');
Route::post('/auth', 'UserController@login');


Route::delete('/user/{id}', 'UserController@delete');
Route::put('/user/{id}', 'UserController@update');
Route::get('/users', 'UserController@index');
Route::get('/get/{id}', 'UserController@getUserById');



//Routes pour Video
Route::post('/user/{id}/video', 'VideoController@create');
Route::get('/videos', 'VideoController@videoindex');
Route::get('/user/{id}/videos', 'UserController@getUserById');





