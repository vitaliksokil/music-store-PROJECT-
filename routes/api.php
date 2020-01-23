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
Route::group(['namespace' => 'Api'], function (){
    Route::post('/login', 'AuthController@login');
    Route::post('/register', 'AuthController@register');
    Route::post('/send-reset-password-email', 'AuthController@sendResetPasswordEmail');
    Route::post('/reset-password', 'AuthController@resetPassword');
    Route::get('/verify-reset-token/{token}-{email}', 'AuthController@verifyResetToken');

});

Route::middleware('auth:api')->group(function () {
    Route::group(['namespace' => 'Auth'], function () {
        Route::post('/send-verification-email', 'EmailVerificationController@sendVerificationEmail');
        Route::get('/verify-email/{token}', 'EmailVerificationController@verify');
    });
    Route::group(['namespace' => 'Api'], function () {
        Route::post('/logout', 'AuthController@logout');
        Route::get('/get-user', 'AuthController@getUser');

        Route::group(['prefix' => 'user'], function () {
            Route::get('/', 'UserController@index');
            Route::get('/{id}', 'UserController@user');
            Route::delete('/{id}', 'UserController@destroy');
            Route::post('/', 'UserController@store');
            Route::put('/{id}', 'UserController@update');
        });
        Route::get('/findUser', 'UserController@search');

        Route::put('/profile', 'UserController@profileUpdate');
        Route::post('/update-profile', 'UserController@updateProfile');
        Route::post('/update-email', 'UserController@updateEmail');
    });


    //products
    Route::post('/product-create', 'ProductController@create');
    Route::get('/products-get', 'ProductController@getProducts');
    Route::get('/product-get/{id}', 'ProductController@getProductByID');
    Route::put('/product-edit/{id}', 'ProductController@update');
    Route::delete('/product-destroy/{id}', 'ProductController@destroy');
    Route::get('/product-category', 'ProductController@getProductCategory');

    Route::put('/site-info', 'SiteController@update');


    //feedbacks
    Route::post('/add-feedback', 'FeedbackController@create');
    Route::post('/is-user-left-feedback', 'FeedbackController@isUserLeftFeedback');
    Route::put('/feedback', 'FeedbackController@update');
    Route::delete('/feedback/{id}', 'FeedbackController@delete');
    Route::get('/feedback/get-likes/{id}', 'FeedbackController@getLikes');

    //likes
    Route::post('/like', 'LikeController@like');


    // shopping cart
    Route::group(['prefix' => 'shopping-cart'], function (){
        Route::get('/', 'ShoppingCartController@show');
        Route::put('/{quantity}&{product_id}', 'ShoppingCartController@updateQuantity');
        Route::post('/', 'ShoppingCartController@create');
        Route::delete('/{product_id}', 'ShoppingCartController@delete');
        Route::post('/delete-all', 'ShoppingCartController@deleteAll');
        Route::get('/count', 'ShoppingCartController@count');
        Route::get('/is-exists/{product_id}', 'ShoppingCartController@isAlreadyInShoppingCart');

    });
    // wishlist
    Route::group(['prefix' => 'wishlist'], function (){
        Route::get('/', 'WishlistController@show');
        Route::post('/', 'WishlistController@create');
        Route::delete('/{product_id}', 'WishlistController@delete');
        Route::post('/delete-all', 'WishlistController@deleteAll');
        Route::post('/add-to-shopping-cart', 'WishlistController@addToShoppingCart');
        Route::post('/add-all-to-shopping-cart', 'WishlistController@addAllToShoppingCart');
        Route::get('/count', 'WishlistController@count');
        Route::get('/is-exists/{product_id}', 'WishlistController@isAlreadyInWishlist');

    });
    Route::group(['prefix' => 'category'],function (){
        Route::post('/','CategoryController@store');
        Route::delete('/{category}','CategoryController@destroy');
        Route::put('/{category}','CategoryController@update');
    });

    Route::group(['prefix' => 'order'],function (){
        Route::post('/','OrderController@store');
        Route::get('/','OrderController@myOrders');
        Route::get('/unverified-orders','OrderController@unverifiedOrders'); // for admin!
        Route::get('/byUserID/{user_id}','OrderController@ordersByUserID'); // for admin!
        Route::put('/verifyAllByUserID/{user_id}','OrderController@verifyAllByUserID'); // for admin!
        Route::put('/verify/{order_id}','OrderController@verify'); // for admin!
        Route::get('/by-id/{order_id}','OrderController@orderByID'); // for admin!
    });
    Route::group(['prefix' => 'payment'],function (){
        Route::get('/','PaymentController@show');
    });
});
Route::group(['prefix' => 'payment'],function (){
    Route::post('/','PaymentController@pay');
    Route::post('/piece-payment','PaymentController@piecePayment');
});
Route::get('/findProduct', 'ProductController@search');
Route::get('/get-recommended-products/{id}', 'ProductController@getRecommendedProducts'); // id - it's product's id
Route::get('/product-get-current/{id}', 'ProductController@getCurrentProductByID');
Route::get('/new-products', 'ProductController@getNewProducts');
Route::get('/products-random', 'ProductController@random');

Route::get('/category','CategoryController@index');
Route::get('/category/{category}','CategoryController@show');


Route::get('/site-info', 'SiteController@getInfo');
Route::get('/category-children', 'CategoryController@getChildren');
Route::get('/findCategory', 'CategoryController@search');
Route::get('/get-products-by-category/{id}', 'CategoryController@getProductsByCategory');
