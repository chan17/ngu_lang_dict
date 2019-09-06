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
    return view('welcome');
});

Route::get('fullentry/index','Api\FullEntryController@index');
Route::get('fullentry/detail','Api\FullEntryController@detail');

Route::get('metatype/listTree/{group}','Api\MetaTypeController@listTree');
Route::get('metatype/list/{group}','Api\MetaTypeController@list');
