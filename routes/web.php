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

Route::get('/', 'FrontendController@index')->name('frontend.index');


Route::get('/camp', 'FrontendController@camp')->name('frontend.offer_camp');
Route::get('/lead', 'FrontendController@lead')->name('frontend.offer_lead');
