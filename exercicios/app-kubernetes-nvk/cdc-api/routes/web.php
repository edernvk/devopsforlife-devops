<?php

Route::post('/login/oauth', 'Auth\AuthController@login')->name('login.oauth');
Route::post('/logout/oauth', 'Auth\AuthController@logout')->name('logout.oauth')->middleware('auth:api');

Route::post('password/forgot', 'Auth\ForgotPasswordController@store');
Route::post('password/check-token', 'Auth\ResetPasswordController@checkToken');
Route::post('password/reset', 'Auth\ForgotPasswordController@reset');

Route::get('/social/auth/{provider}', 'Auth\AuthController@redirect');
Route::get('/social/auth/{provider}/callback', 'Auth\AuthController@callback');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
