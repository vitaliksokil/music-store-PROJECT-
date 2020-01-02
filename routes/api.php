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

Route::post('/login', 'Api\AuthController@login');
Route::post('/register', 'Api\AuthController@register');
Route::post('/send-reset-password-email', 'Api\AuthController@sendResetPasswordEmail');
Route::post('/reset-password', 'Api\AuthController@resetPassword');
Route::get('/verify-reset-token/{token}-{email}', 'Api\AuthController@verifyResetToken');
Route::get('/product-get-current/{id}', 'ProductController@getCurrentProductByID');

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', 'API\AuthController@logout');
    Route::get('/get-user', 'API\AuthController@getUser');
    Route::post('/send-verification-email', 'Auth\EmailVerificationController@sendVerificationEmail');
    Route::get('/verify-email/{token}', 'Auth\EmailVerificationController@verify');

    Route::get('/findUser','Api\UserController@search');
    Route::get('/user','Api\UserController@index');
    Route::get('/user/{id}','Api\UserController@user');
    Route::delete('/user/{id}','Api\UserController@destroy');
    Route::post('/user','Api\UserController@store');
    Route::put('/user/{id}','Api\UserController@update');

    Route::put('/profile','API\UserController@profileUpdate');
    Route::post('/update-profile', 'Api\UserController@updateProfile');
    Route::post('/update-email', 'Api\UserController@updateEmail');

    //products
    Route::post('/product-create', 'ProductController@create');
    Route::get('/products-get', 'ProductController@getProducts');
    Route::get('/product-get/{id}', 'ProductController@getProductByID');
    Route::put('/product-edit/{id}', 'ProductController@update');
    Route::delete('/product-destroy/{id}', 'ProductController@destroy');
    Route::get('/product-category', 'ProductController@getProductCategory');

    Route::put('/site-info', 'SiteController@update');


    //feedbacks
    Route::post('/add-feedback','FeedbackController@create');
    Route::post('/is-user-left-feedback','FeedbackController@isUserLeftFeedback');
    Route::put('/feedback','FeedbackController@update');
    Route::delete('/feedback/{id}','FeedbackController@delete');
    Route::get('/feedback/get-likes/{id}','FeedbackController@getLikes');

    //likes
    Route::post('/like','LikeController@like');


    // shopping cart
    Route::get('/shopping-cart', 'ShoppingCartController@show');
    Route::post('/shopping-cart', 'ShoppingCartController@create');
    Route::delete('/shopping-cart/{product_id}', 'ShoppingCartController@delete');
    Route::get('/shopping-cart/count', 'ShoppingCartController@count');
    Route::get('/shopping-cart/is-exists/{product_id}', 'ShoppingCartController@isAlreadyInShoppingCart');


});

Route::get('/findProduct', 'ProductController@search');
Route::get('/get-recommended-products/{id}', 'ProductController@getRecommendedProducts'); // id - it's product's id

Route::get('/site-info', 'SiteController@getInfo');
Route::get('/category-children','CategoryController@getChildren');
Route::get('/findCategory','CategoryController@search');
Route::resource('category', 'CategoryController');
Route::get('/get-products-by-category/{id}','CategoryController@getProductsByCategory');
