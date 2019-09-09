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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
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

});
