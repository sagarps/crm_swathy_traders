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
    return view('auth/login');
});

Auth::routes();

require 'masters.php';
require 'sales_marketing.php';
require 'route_report.php';
require 'route_chart.php';
require 'route_complaint.php';
require 'route_payment.php';
require 'route_security.php';

Route::get('/home', 'HomeController@index')->name('home');
