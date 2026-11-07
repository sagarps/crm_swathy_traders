<?php

//office//
Route::group(['middleware' => ['check.authPermission:10']], function(){

  Route::get('office/index',['as'=>'office.index',
  'uses'=>'OfficeController@index']);

  Route::get('office/create',['as'=>'office.create', 
  'uses'=>'OfficeController@create']);

  Route::post('office/store',['as'=>'office.store',
  'uses'=>'OfficeController@store']);

  Route::get('office/edit/{id}',['as'=>'office.edit', 
  'uses'=>'OfficeController@edit']);
   
  Route::post('office/update',['as'=>'office.update',
  'uses'=>'OfficeController@update']);

  Route::post('reset/password/{id}',['as'=>'reset.password',
  'uses'=>'OfficeController@resetPassword']);

});

Route::group(['middleware' => ['check.authPermission:1']], function(){

  Route::get('admin/office/index',['as'=>'admin.office.index',
  'uses'=>'OfficeController@indexAdmin']);

  Route::get('admin/office/create',['as'=>'admin.office.create', 
  'uses'=>'OfficeController@createAdmin']);

  Route::post('admin/office/store',['as'=>'admin.office.store',
  'uses'=>'OfficeController@storeAdmn']);

  Route::get('admin/office/edit/{id}',['as'=>'admin.office.edit', 
  'uses'=>'OfficeController@editAdmin']);
   
  Route::post('admin/office/update',['as'=>'admin.office.update',
  'uses'=>'OfficeController@updateAdmin']);

  Route::post('admin.reset/password/{id}',['as'=>'admin.reset.password',
  'uses'=>'OfficeController@resetPasswordAdmin']);

});

//unit//
Route::group(['middleware' => ['check.authPermission:5']], function(){
  
  Route::get('unit/index',['as' => 'unit.index',
   'uses' => 'UnitController@index']);

  Route::get('unit/create',['as' => 'unit.create',
   'uses' => 'UnitController@create']);

  Route::post('unit/store',['as' => 'unit.store',
   'uses' => 'UnitController@store']);

  Route::get('unit/edit/{id}',['as' => 'unit.edit',
   'uses' => 'UnitController@edit']);

  Route::post('unit/update',['as' => 'unit.update',
   'uses' => 'UnitController@update']);

});

Route::group(['middleware' => ['check.authPermission:3']], function(){
//category//
  Route::get('product/category/index',['as'=>'product.category.index',
  'uses'=>'ProductCategoryController@index']);

  Route::post('product/category/store',['as'=>'product.category.store',
  'uses'=>'ProductCategoryController@store']);

  Route::get('product/category/edit',['as'=>'product.category.edit',
  'uses'=>'ProductCategoryController@edit']);

  Route::get('add/category/brand',['as'=>'add.category.brand',
  'uses'=>'ProductCategoryController@mapBrand']);

  Route::get('delete/category/brand',['as'=>'delete.category.brand',
  'uses'=>'ProductCategoryController@deleteBrand']);

  Route::get('add/category/size',['as'=>'add.category.size',
  'uses'=>'ProductCategoryController@mapSize']);

  Route::get('delete/category/size',['as'=>'delete.category.size',
  'uses'=>'ProductCategoryController@deleteSize']);

  Route::post('product/category/update',['as'=>'product.category.update',
  'uses'=>'ProductCategoryController@update']);

// Sub category//

  Route::post('sub_category/store',['as'=>'sub_category.store',
  'uses'=>'ProductCategoryController@storeSub']);

  Route::get('sub_category/edit',['as'=>'sub_category.edit',
    'uses'=>'ProductCategoryController@editSub']);

  Route::post('sub_category/update',['as'=>'sub_category.update',
    'uses'=>'ProductCategoryController@updateSub']);

//group

  Route::post('group/store',['as'=>'group.store',
  'uses'=>'ProductCategoryController@storeGroup']);

  Route::get('group/edit',['as'=>'group.edit',
    'uses'=>'ProductCategoryController@editGroup']);

  Route::post('group/update',['as'=>'group.update',
    'uses'=>'ProductCategoryController@updateGroup']);

//sub group

  Route::post('sub_group/store',['as'=>'sub_group.store',
  'uses'=>'ProductCategoryController@storeSubGroup']);

  Route::get('subgroup/edit','ProductCategoryController@editSubgrp');

  Route::post('sub_group/update',['as'=>'sub_group.update',
  'uses'=>'ProductCategoryController@updateSubGroup']);

});

//product//
Route::group(['middleware' => ['check.authPermission:4']], function(){

  Route::get('product/index',['as' => 'product.index',
    'uses' => 'ProductController@index']); //

  Route::get('product/create',['as' => 'product.create',
    'uses' => 'ProductController@create']); //

  Route::post('product/insert',['as' => 'product.insert',
    'uses' => 'ProductController@insert']); //

  Route::get('otherUnits/{id}','ProductController@otherUnits'); //

  Route::post('otherUnits/insert',['as'=> 'otherUnits.insert',
    'uses' => 'ProductController@insertUnits']); //

  Route::get('get/subCategory','ProductController@getSubCat'); // 

  Route::get('get/subgroup/products','ProductController@getSubGroup'); //

  Route::get('product/edit/{id}',['as' => 'product.edit',
    'uses' => 'ProductController@edit']); //

  Route::post('product/update',['as' => 'product.update',
    'uses' => 'ProductController@update']); //

  Route::post('otherUnits/update/{id}',['as'=> 'otherUnits.update',
    'uses' => 'ProductController@updateUnits']); //

  Route::get('delete/otherUnit','ProductController@deleteOtherUnit'); //

});

//Designation//
Route::group(['middleware' => ['check.authPermission:7']], function(){

  Route::get('designation/index',['as' => 'designation.index',
    'uses' => 'DesignationController@index']); 
    
  Route::post('designaton/store',['as' => 'designaton.store',
    'uses' => 'DesignationController@store']); 
    
  Route::post('designaton/update',['as' => 'designaton.update',
    'uses' => 'DesignationController@update']);

});

//Employee//
Route::group(['middleware' => ['check.authPermission:8']], function(){

    Route::get('employee/index',['as' => 'employee.index',
      'uses' => 'EmployeeController@index']); 

    Route::get('employee/create',['as' => 'employee.create',
      'uses' => 'EmployeeController@create']);

    Route::post('employee/store',['as' => 'employee.store',
      'uses' => 'EmployeeController@store']);

    Route::get('employee/edit/{id}',['as' => 'employee.edit',
      'uses' => 'EmployeeController@edit']);

    Route::post('employee/update',['as' => 'employee.update',
      'uses' => 'EmployeeController@updateData']);

});

//Cnfiguration//
Route::group(['middleware' => ['check.authPermission:6']], function(){
 
    Route::get('config/index',['as'=>'config.index',
      'uses'=>'ConfigController@index']);

    Route::post('config/store',['as'=>'config.store',
      'uses'=>'ConfigController@store']);

    Route::get('config/edit',['as'=>'config.edit',
      'uses'=>'ConfigController@edit']);

    Route::get('config/delete',['as'=>'config.delete',
      'uses'=>'ConfigController@delete']);

});

// User // 
Route::group(['middleware' => ['check.authPermission:9']], function(){

    Route::get('user/index',['as'=>'user.index',
    'uses'=>'UserController@index']);

    Route::get('user/create',['as'=>'user.create', 
    'uses'=>'UserController@create']);

    Route::post('user/store',['as'=>'user.store',
    'uses'=>'UserController@store']);

    Route::get('user/edit/{id}',['as'=>'user.edit', 
    'uses'=>'UserController@edit']);

    Route::post('user/update',['as'=>'user.update',
    'uses'=>'UserController@update']);

});

// Role //
Route::group(['middleware' => ['check.authPermission:2']], function(){

    Route::get('role/index',['as'=>'role.index',
      'uses'=>'RoleController@index']);

    Route::get('role/create',['as'=>'role.create',
        'uses'=>'RoleController@create']);

    Route::post('role/store',['as'=>'role.store',
        'uses'=>'RoleController@store']);

    Route::get('role/edit/{id}',['as'=>'role.edit',
        'uses'=>'RoleController@edit']);

    Route::post('role/update',['as'=>'role.update',
        'uses'=>'RoleController@update']);

    Route::get('switch/user/{id}',['as'=>'switch.user',
        'uses'=>'RoleController@switchUser']);

});

//product price
Route::group(['middleware' => ['check.authPermission:14']], function(){

    Route::get('price/list/index',['as'=>'price.list.index',
      'uses'=>'PriceController@index']);

    Route::post('price.update',['as'=>'price.update',
      'uses'=>'PriceController@priceUpdate']);

    Route::get('other/units','PriceController@otherUnits');
    
    Route::get('other/units/update','PriceController@otherUnitsUpdate');

});

Route::group(['middleware' => ['check.authPermission:24']], function(){
  Route::get('/customers', ['as' => 'customers.index','uses' => 'CustomerController@index']);
  Route::get('customers/create', ['as' => 'customers.create', 'uses' => 'CustomerController@create']);
  Route::post('customers/store', ['as' => 'customers.store', 'uses' => 'CustomerController@store']);
  Route::get('customers/edit/{id}', ['as' => 'customers.edit', 'uses' => 'CustomerController@edit']);
  Route::post('customers/update/{id}', ['as' => 'customers.update', 'uses' => 'CustomerController@update']);
  Route::get('customers/destroy/{id}', ['as' => 'customers.destroy', 'uses' => 'CustomerController@destroy']);
  Route::get('customers/show/{id}', ['as' => 'customers.show', 'uses' => 'CustomerController@show']);
});

Route::get('add/customertype',['as'=>'add.customertype',
  'uses'=>'CustomerController@addCustomerType']);

Route::get('add/customerclassification',['as'=>'add.customerclassification',
  'uses'=>'CustomerController@addCustomerClassification']);


// Customer Classifications
Route::group(['middleware' => ['check.authPermission:24']], function(){
  Route::get('customer-classifications', ['as' => 'customer_classifications.index', 'uses' => 'CustomerClassificationController@index']);
  Route::get('customer-classifications/create', ['as' => 'customer_classifications.create', 'uses' => 'CustomerClassificationController@create']);
  Route::post('customer-classifications/store', ['as' => 'customer_classifications.store', 'uses' => 'CustomerClassificationController@store']);
  Route::get('customer-classifications/edit/{id}', ['as' => 'customer_classifications.edit', 'uses' => 'CustomerClassificationController@edit']);
  Route::post('customer-classifications/update/{id}', ['as' => 'customer_classifications.update', 'uses' => 'CustomerClassificationController@update']);
  Route::get('customer-classifications/destroy/{id}', ['as' => 'customer_classifications.destroy', 'uses' => 'CustomerClassificationController@destroy']);
});
