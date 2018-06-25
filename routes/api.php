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
Route::post('/login', 'Api\AdminController@login');
Route::post('/register', 'Api\AdminController@register');

Route::get('/customer', 'Api\AdminController@customer');
Route::get('/logout', 'Api\AdminController@logout');
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
