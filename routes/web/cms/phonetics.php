<?php

/**
 * 请记得打注释, 小心被小田怼
 */
Route::get('cms/phonetics', ['as'=> 'cms.phonetics.index', 'uses' => 'Cms\PhoneticController@index']);
Route::post('cms/phonetics', ['as'=> 'cms.phonetics.store', 'uses' => 'Cms\PhoneticController@store']);
Route::get('cms/phonetics/create', ['as'=> 'cms.phonetics.create', 'uses' => 'Cms\PhoneticController@create']);
Route::put('cms/phonetics/{phonetics}', ['as'=> 'cms.phonetics.update', 'uses' => 'Cms\PhoneticController@update']);
Route::patch('cms/phonetics/{phonetics}', ['as'=> 'cms.phonetics.update', 'uses' => 'Cms\PhoneticController@update']);
Route::delete('cms/phonetics/{phonetics}', ['as'=> 'cms.phonetics.destroy', 'uses' => 'Cms\PhoneticController@destroy']);
Route::get('cms/phonetics/{phonetics}', ['as'=> 'cms.phonetics.show', 'uses' => 'Cms\PhoneticController@show']);
Route::get('cms/phonetics/{phonetics}/edit', ['as'=> 'cms.phonetics.edit', 'uses' => 'Cms\PhoneticController@edit']);
