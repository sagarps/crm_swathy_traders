<?php

//Report//
Route::group(['middleware' => ['auth']], function(){

  Route::get('enquiry/report',['as'=>'enquiry.report',
  'uses'=>'ReportController@enqIndex']);

  Route::get('enquiry/report/show',['as'=>'enquiry.report.show',
  'uses'=>'ReportController@enqShow']);

  Route::get('complaint/report',['as'=>'complaint.report',
  'uses'=>'ReportController@cmpIndex']);

  Route::get('complaint/report/show',['as'=>'complaint.report.show',
  'uses'=>'ReportController@cmpShow']);

  Route::get('freebie/report',['as'=>'freebie.report',
  'uses'=>'ReportController@getFreebieReport']);


  Route::get('freebie/report/show',['as'=>'freebie.report.show',
  'uses'=>'ReportController@showFreebieReport']);

  Route::get('performance/report',['as'=>'performance.report',
  'uses'=>'ReportController@perIndex']);

  Route::get('performance/report/show',['as'=>'performance.report.show',
  'uses'=>'ReportController@perShow']);

  Route::get('activity/report',['as'=>'activity.report',
  'uses'=>'ReportController@actIndex']);

  Route::get('activity/report/show',['as'=>'activity.report.show',
  'uses'=>'ReportController@actShow']);
  
  Route::get('collection/followup/report',['as'=>'collection.followup.report',
  'uses'=>'ReportController@followupReport']);

  Route::get('collection/followup/report/show',['as'=>'collection.followup.report.show',
  'uses'=>'ReportController@followupReportShow']);

});


