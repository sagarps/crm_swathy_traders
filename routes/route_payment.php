<?php
Route::group(['middleware' => ['check.authPermission:25','auth']], function(){

  Route::get('collection/followups/index',['as'=>'collection.followups.index', 
  'uses'=>'CollectionController@followupShow']);

  Route::get('collection/followup/edit/{id}/{from}/{to}/{folotype?}',['as'=>'collection.followup.edit', 
  'uses'=>'CollectionController@editFollowup']);

  Route::get('get/followups','CollectionController@getFollowups');

  Route::post('collection/followup/update',['as'=>'collection.followup.update', 
  'uses'=>'CollectionController@updateFollowup']); 

  Route::post('collection/search/followups',['as'=>'collection.search.followups', 
  'uses'=>'CollectionController@searchFollowups']);

  Route::post('collection/bill/save',['as'=>'collection.bill.save', 
  'uses'=>'CollectionController@saveBillDetails']);
  Route::post('collection/bill/delete',['as'=>'collection.bill.delete', 
  'uses'=>'CollectionController@deleteBillDetails']);

  Route::get('collection/log','CollectionController@collectionLog');
  
});