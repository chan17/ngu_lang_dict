<?php

/**
 * 请记得打注释, 小心被小田怼
 */
Route::get('meta/meta_type/{group}', ['as'=> 'meta.meta_type.index', 'uses' => 'Meta\MetaTypeController@index']);
Route::post('meta/meta_type/{group}', ['as'=> 'meta.meta_type.store', 'uses' => 'Meta\MetaTypeController@store']);
Route::get('meta/meta_type/{group}/create', ['as'=> 'meta.meta_type.create', 'uses' => 'Meta\MetaTypeController@create']);
Route::put('meta/meta_type/{group}/{meta_type}', ['as'=> 'meta.meta_type.update', 'uses' => 'Meta\MetaTypeController@update']);
Route::patch('meta/meta_type/{group}/{meta_type}', ['as'=> 'meta.meta_type.update', 'uses' => 'Meta\MetaTypeController@update']);
Route::delete('meta/meta_type/{group}/{meta_type}', ['as'=> 'meta.meta_type.destroy', 'uses' => 'Meta\MetaTypeController@destroy']);
Route::get('meta/meta_type/{group}/{meta_type}', ['as'=> 'meta.meta_type.show', 'uses' => 'Meta\MetaTypeController@show']);
Route::get('meta/meta_type/{group}/{meta_type}/edit', ['as'=> 'meta.meta_type.edit', 'uses' => 'Meta\MetaTypeController@edit']);

Route::post('meta/meta_type/change_order_list', ['as'=> 'meta.meta_type.change_order_list', 'uses' => 'Meta\MetaTypeController@change_order_list']);
