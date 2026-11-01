<?php

// Team Mapping //
Route::group(['middleware' => ['check.authPermission:13','auth']], function(){
  
  	Route::get('team/mapping/index',['as'=>'team.mapping.index',
        'uses'=>'TeamMappingController@index']);

  	Route::post('team/mapping/store',['as' => 'team.mapping.store',
        'uses' => 'TeamMappingController@store']); 
    
  	Route::post('team/mapping/update',['as' => 'team.mapping.update',
        'uses' => 'TeamMappingController@update']); 
      
  	Route::get('member/list','TeamMappingController@memberList');

  	Route::get('parent/list','TeamMappingController@parentList');

  	Route::get('edit/member/list','TeamMappingController@editMemberList');
      
  	Route::get('delete/member/list','TeamMappingController@deleteMemberList');

});

//ENQUIRY
Route::group(['middleware' => ['check.authPermission:11','auth']], function(){

  Route::get('enquiry/index',['as'=>'enquiry.index',
    'uses'=>'EnquiryController@index']);

  Route::get('enquiry/create/{from}/{to}',['as'=>'enquiry.create', 
    'uses'=>'EnquiryController@create']);

  Route::get('NEWLEAD/{from}/{to}','EnquiryController@newLead');

  Route::get('FOLLOWUP/{from}/{to}','EnquiryController@followUP');
  
  Route::get('SALECLOSE/{from}/{to}','EnquiryController@saleClose');
  
  Route::get('LOST/{from}/{to}','EnquiryController@lost');

  Route::get('CANCEL/{from}/{to}','EnquiryController@cancel');

  Route::post('enquiry/store',['as'=>'enquiry.store',
    'uses'=>'EnquiryController@store']);

  Route::get('enquiry/edit/{id}/{from}/{to}',['as'=>'enquiry.edit', 
    'uses'=>'EnquiryController@edit']);

  Route::get('select/place','EnquiryController@selectPlace');

  Route::get('customer/list','EnquiryController@customerList');

  Route::post('enquiry/update',['as'=>'enquiry.update', 
    'uses'=>'EnquiryController@update']); 

  Route::get('enquiry/log','EnquiryController@enqLog');

  Route::get('check/phone','EnquiryController@checkPhone');

  Route::get('categort/products','EnquiryController@catProduct');

  Route::get('product/units','EnquiryController@proUnits');

  Route::get('product/store','EnquiryController@proStore');

  Route::get('unit/price','EnquiryController@unitPrice');

  Route::get('product/edit','EnquiryController@proEdit');

  Route::get('product/delete','EnquiryController@proDelete');

  Route::post('enquiry/save',['as'=>'enquiry.save', 
    'uses'=>'EnquiryController@enquirySave']);

  Route::get('enquiry/view/{id}/{from}/{to}',['as'=>'enquiry.view', 
    'uses'=>'EnquiryController@viewItem']);

});

//ADMIN ENQUIRY
Route::group(['middleware' => ['check.authPermission:16','auth']], function(){

  Route::get('admin/enquiry/index',['as'=>'admin.enquiry.index',
    'uses'=>'AdminEnquiryController@index']);

  Route::get('admin/enquiry/create/{from}/{to}',['as'=>'admin.enquiry.create', 
    'uses'=>'AdminEnquiryController@create']);

  Route::get('admin/NEWLEAD/{from}/{to}','AdminEnquiryController@newLead');

  Route::get('admin/FOLLOWUP/{from}/{to}','AdminEnquiryController@followUP');
  
  Route::get('admin/SALECLOSE/{from}/{to}','AdminEnquiryController@saleClose');
  
  Route::get('admin/CANCEL/{from}/{to}','AdminEnquiryController@cancel');

  Route::get('admin/LOST/{from}/{to}','AdminEnquiryController@Llost');
 
  Route::post('admin/enquiry/store',['as'=>'admin.enquiry.store',
    'uses'=>'AdminEnquiryController@store']);

  Route::get('admin/enquiry/edit/{id}/{from}/{to}',['as'=>'admin.enquiry.edit', 
    'uses'=>'AdminEnquiryController@edit']);

  Route::get('admin/customer/list','AdminEnquiryController@customerList');

  Route::get('admin/customer/show','AdminEnquiryController@customerShow');

  Route::post('admin/enquiry/update',['as'=>'admin.enquiry.update', 
    'uses'=>'AdminEnquiryController@update']);  //

  Route::get('admin/enquiry/log','AdminEnquiryController@enqLog');

  Route::get('admin/check/phone','AdminEnquiryController@checkPhone');

  Route::get('admin/categort/products','AdminEnquiryController@catProduct');

  Route::get('admin/product/units','AdminEnquiryController@proUnits');

  Route::get('admin/product/store','AdminEnquiryController@proStore');

  Route::get('admin/unit/price','AdminEnquiryController@unitPrice');

  Route::get('admin/product/edit','AdminEnquiryController@proEdit');

  Route::get('admin/product/delete','AdminEnquiryController@proDelete');

  Route::post('admin/enquiry/save',['as'=>'admin.enquiry.save', 
    'uses'=>'AdminEnquiryController@enquirySave']); //

  Route::get('admin/enquiry/view/{id}/{from}/{to}',['as'=>'admin.enquiry.view', 
    'uses'=>'AdminEnquiryController@viewItem']);

});

  // TEAM'S ENQUIRIES
Route::group(['middleware' => ['check.authPermission:12','auth']], function(){

  Route::get('team/enquiry/index',['as'=>'team.enquiry.index',
    'uses'=>'TeamEnquiryController@index']);

  Route::get('team/NEWLEAD/{from}/{to}','TeamEnquiryController@newLead');

  Route::get('team/FOLLOWUP/{from}/{to}','TeamEnquiryController@followUP');
  
  Route::get('team/SALECLOSE/{from}/{to}','TeamEnquiryController@saleClose');
  
  Route::get('team/CANCEL/{from}/{to}','TeamEnquiryController@cancel');

  Route::get('team/LOST/{from}/{to}','TeamEnquiryController@lost');

  Route::get('team/enquiry/view/{id}/{from}/{to}',['as'=>'team.enquiry.view', 
    'uses'=>'TeamEnquiryController@viewItem']);

  Route::get('team/enquiry/log','TeamEnquiryController@enqLog');

});

Route::group(['middleware' => ['check.authPermission:19','auth']], function(){
  //Esclate followup//
  Route::get('escalated/followup/index',['as'=>'escalated.followup.index',
    'uses'=>'EsclatedFollowupController@index']);

  Route::get('escalated/followup/edit/{id}',['as'=>'escalated.followup.edit',
    'uses'=>'EsclatedFollowupController@edit']);

  Route::post('escalated/followup/update/{id}',['as'=>'escalated.followup.update',
    'uses'=>'EsclatedFollowupController@update']);

  Route::post('esclated/followup/save',['as'=>'esclated.followup.save', 
    'uses'=>'EsclatedFollowupController@esclatedSave']);

});

Route::group(['middleware' => ['check.authPermission:20','auth']], function(){

  Route::get('followups/index',['as'=>'followups.index', 
  'uses'=>'EnquiryController@followupShow']);

  Route::get('followup/edit/{id}/{from}/{to}',['as'=>'followup.edit', 
  'uses'=>'EnquiryController@editFollowup']);

  Route::get('get/followups/enquiry','EnquiryController@getFollowups');

  Route::post('followup/update',['as'=>'followup.update', 
  'uses'=>'EnquiryController@updateFolloup']); 

  Route::post('search/followups',['as'=>'search.followups', 
  'uses'=>'EnquiryController@searchFollowups']);


});

Route::group(['middleware' => ['check.authPermission:21','auth']], function(){

  Route::get('get/freebieIssues','FreebieController@getFreebieIssues');

  Route::get('add/freebie','FreebieController@addFreebieIssueType');

  Route::post('freebie_issue/create',['as'=>'create.freebie_issue',
  'uses'=>'FreebieController@create']);

  Route::get('show/freebie_issues',['as'=>'show.freebie_issues',
  'uses'=>'FreebieController@showFreebieIssues']);

  Route::get('freebie/issue',['as'=>'freebie.issue', 
  'uses'=>'FreebieController@createFreebie']);

  Route::get('check/freebie/issues',['as'=>'check.freebie.issue', 
  'uses'=>'FreebieController@checkFreebieIssues']);
});

Route::group(['middleware' => ['check.authPermission:22','auth']], function(){

  Route::get('associate/list','BusinessAssociateController@searchAssociates');


  Route::get('associate/showDetails','BusinessAssociateController@showAssociateDetails');

  Route::get('business_associate/index',['as'=>'add.associate', 
  'uses'=>'BusinessAssociateController@addBusinessAssociate']);

  Route::get('associate/index',['as'=>'associate.index', 
  'uses'=>'BusinessAssociateController@getBusinessAssociates']);

  Route::get('associate/create',['as'=>'associate.create', 
  'uses'=>'BusinessAssociateController@createBusinessAssociate']);

  Route::get('associate/edit/{id}',['as'=>'associate.edit', 
  'uses'=>'BusinessAssociateController@editBusinessAssociate']);

    Route::post('associate/update',['as'=>'associate.update', 
  'uses'=>'BusinessAssociateController@updateBusinessAssociate']);

  Route::post('associate/create',['as'=>'create.associate',
  'uses'=>'BusinessAssociateController@createAssociate']);

});

Route::group(['middleware' => ['auth']], function(){
  Route::get('quotation/store','EnquiryController@quotStore');

  Route::get('admin/quotation/store','AdminEnquiryController@quotStore');

  Route::get('quotation/check','EnquiryController@quotCheck');
  
  Route::get('admin/quotation/check','AdminEnquiryController@quotCheck');

  Route::get('associate/show','BusinessAssociateController@showAssociates');

  Route::get('add/place',['as'=>'add.place', 'uses'=>'EnquiryController@addPlace']);

  Route::get('add/function','EnquiryController@addFunction');

  Route::get('add/associate','BusinessAssociateController@addAssociate');

  Route::get('customer/show','EnquiryController@customerShow');


});
 

  

 

  