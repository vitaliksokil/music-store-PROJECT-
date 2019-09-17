<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login','Api\AuthController@login');
Route::post('/register','Api\AuthController@register');
Route::post('/send-reset-password-email','Api\AuthController@sendResetPasswordEmail');
Route::post('/reset-password','Api\AuthController@resetPassword');
Route::get('/verify-reset-token/{token}-{email}','Api\AuthController@verifyResetToken');
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', 'API\AuthController@logout');
    Route::get('/get-user', 'API\AuthController@getUser');
    Route::post('/send-verification-email', 'Auth\EmailVerificationController@sendVerificationEmail');
    Route::get('/verify-email/{token}', 'Auth\EmailVerificationController@verify');
    Route::post('/update-profile','Api\UserController@updateProfile');
    Route::post('/update-email','Api\UserController@updateEmail');

    //products
    Route::post('/product-create','ProductController@create');
    Route::get('/products-get','ProductController@getProducts');
    Route::get('/product-get/{id}','ProductController@getProductByID');
    Route::put('/product-edit/{id}','ProductController@update');
    Route::delete('/product-destroy/{id}','ProductController@destroy');
});
