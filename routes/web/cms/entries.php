<?php

/**
 * 请记得打注释, 小心被小田怼
 */
Route::get('cms/entries', ['as'=> 'cms.entries.index', 'uses' => 'Cms\EntryController@index']);
Route::post('cms/entries', ['as'=> 'cms.entries.store', 'uses' => 'Cms\EntryController@store']);
Route::get('cms/entries/create', ['as'=> 'cms.entries.create', 'uses' => 'Cms\EntryController@create']);
Route::put('cms/entries/{entries}', ['as'=> 'cms.entries.update', 'uses' => 'Cms\EntryController@update']);
Route::patch('cms/entries/{entries}', ['as'=> 'cms.entries.update', 'uses' => 'Cms\EntryController@update']);
Route::delete('cms/entries/{entries}', ['as'=> 'cms.entries.destroy', 'uses' => 'Cms\EntryController@destroy']);
Route::get('cms/entries/{entries}', ['as'=> 'cms.entries.show', 'uses' => 'Cms\EntryController@show']);
Route::get('cms/entries/{entries}/edit', ['as'=> 'cms.entries.edit', 'uses' => 'Cms\EntryController@edit']);
