<?php 

Route::group(['middleware' => ['check.authPermission:17']], function(){
  // complaint//
  

  Route::get('complaint/index',['as'=>'complaint.index',
    'uses'=>'ComplaintController@index']);
  
  Route::get('complaint/create',['as'=>'complaint.create',
    'uses'=>'ComplaintController@create']);

  Route::post('complaint/store',['as'=>'complaint.store',
    'uses'=>'ComplaintController@store']);
  
  Route::get('complaint/customer/list','ComplaintController@customerList');

  Route::get('complaint/customer/show','ComplaintController@customerShow');

  Route::get('complaint/type/save','ComplaintController@complaintTypeSave');

  Route::get('complaint/edit/{id}',['as'=>'complaint.edit',
    'uses'=>'ComplaintController@edit']);
  
  Route::post('complaint/update',['as'=>'complaint.update',
    'uses'=>'ComplaintController@update']);
  
  Route::get('complaint/view/{id}',['as'=>'complaint.view',
    'uses'=>'ComplaintController@viewCoomplaint']);

});