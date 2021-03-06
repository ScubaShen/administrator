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

//Test
Route::get('test', 'TestsController@forTest')->name('test');

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

Route::get('index', 'IndexController@index')->name('index');

Route::resource('engineerings', 'EngineeringsController', ['only' => ['index', 'show', 'create', 'store', 'update', 'edit']]);
Route::post('engineerings/upload_image', 'EngineeringsController@uploadImage')->name('engineerings.upload_image');
Route::get('engineerings/{engineering}/view', 'EngineeringsController@getView')->name('engineerings.view');
Route::post('engineerings/results', 'EngineeringsController@getResults')->name('engineerings.result');
Route::delete('engineerings/batch_delete', 'EngineeringsController@destroyAll')->name('engineerings.batch_delete');
Route::post('engineerings/search', 'EngineeringsController@search')->name('engineerings.search');

Route::resource('batches', 'BatchesController', ['only' => ['index', 'show', 'create', 'store', 'update', 'edit']]);
Route::get('batches/{batch}/view', 'BatchesController@getView')->name('batches.view');
Route::post('batches/results', 'BatchesController@getResults')->name('batches.result');
Route::delete('batches/batch_delete', 'BatchesController@destroyAll')->name('batches.batch_delete');
Route::post('batches/search', 'BatchesController@search')->name('batches.search');

Route::resource('members', 'MembersController', ['only' => ['index', 'show', 'create', 'store', 'update']]);
Route::get('members/{member}/view', 'MembersController@getView')->name('members.view');
Route::post('members/results', 'MembersController@getResults')->name('members.result');
Route::delete('members/batch_delete', 'MembersController@destroyAll')->name('members.batch_delete');
Route::post('members/search', 'MembersController@search')->name('members.search');

Route::resource('materials', 'MaterialsController', ['only' => ['index', 'show', 'create', 'store', 'update', 'edit']]);
Route::get('materials/{material}/view', 'MaterialsController@getView')->name('materials.view');
Route::post('materials/results', 'MaterialsController@getResults')->name('materials.result');
Route::delete('materials/batch_delete', 'MaterialsController@destroyAll')->name('materials.batch_delete');
Route::post('materials/search', 'MaterialsController@search')->name('materials.search');