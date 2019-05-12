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


Route::get('/', 'AtdwController@index');
// Route::get('/regions/{state}', 'AtdwController@getRegions');
Route::get('/options/{state}', 'AtdwController@getOptions');
Route::get('/products', 'AtdwController@filterProducts');
//Route::get('/areas/{region}', 'AtdwController@getAreasByRegion');
//Route::get('/areas/{region}', 'AtdwController@getAreasByRegion');
