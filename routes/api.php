<?php

use Illuminate\Support\Facades\Route;

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
/**
 * Routes with not middleware
 */

# Companies
Route::get('products', 'Api\ProductsController@index');
Route::get('locations', 'Api\LocationsController@index');
Route::get('categories/get-categories', 'Api\CategoriesController@getCategories');
Route::get('products/slug/{slug}', 'Api\ProductsController@getProductData');
Route::get('products/search-by-term/{term}', 'Api\ProductsController@searchByTerm');
Route::post('products/filter', 'Api\ProductsController@filter');
Route::get('products/latest', 'Api\ProductsController@latest');
Route::post('contact/send', 'Api\ContactController@send');
Route::get('products/latest', 'Api\ProductsController@latest');
Route::get('payments/resultado-pago', 'Api\PaymentController@mpResponse');
Route::post('payments/mp-response', 'Api\PaymentController@mpIpnResponse')->name('payment.mp.response');
Route::post('newsletter/store-external', 'Api\NewsletterController@storeExternal');

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', 'Auth\LoginController@logout');

    // payments
    Route::get('/payments/generar-pago/{hash}', 'Api\PaymentController@generarPago');
    
    // user
    Route::get('/user', 'Auth\UserController@current');
    Route::post('/user-update', 'Auth\UserController@update');
    Route::post('/set-favorites', 'Auth\UserController@setFavorites');
    Route::get('/remove-favorite/{product_id}', 'Auth\UserController@removeFavorite');
    Route::get('/get-favorites', 'Auth\UserController@getFavorites');
    Route::patch('settings/profile', 'Settings\ProfileController@update');
    Route::patch('settings/password', 'Settings\PasswordController@update');

    // products
    Route::post('products/store-images', 'Api\ProductsController@storeImage');
    Route::post('products/delete-image', 'Api\ProductsController@deleteImage');
    Route::post('products/sort-images', 'Api\ProductsController@sortImages');
    Route::post('products/get-all', 'Api\ProductsController@getAll');
    Route::get('products/export', 'Api\ProductsController@ExportProductList');
    Route::post('products/import', 'Api\ProductsController@ImportProductList');
    
    //categories
    Route::post('categories/store-images', 'Api\CategoriesController@storeImage');
    Route::post('categories/delete-image', 'Api\CategoriesController@deleteImage');
    Route::get('categories/slug/{slug}', 'Api\CategoriesController@getCategoryData');
    Route::post('categories/sort', 'Api\CategoriesController@sort');

    //sales
    Route::post('sales/change-payment-status', 'Api\SalesController@changePaymentStatus');
    Route::get('sales/unread', 'Api\SalesController@getUnread');
    Route::get('sales/set-readed/{sale}', 'Api\SalesController@setReaded');
    Route::get('sales/by-user/{user}', 'Api\SalesController@getByUser');
    Route::get('sales/print/{sale}', 'Api\SalesController@printSale');
    
    // reports
    Route::get('reports/print/sales', 'Api\SalesController@printReport');

    //coupons
    Route::post('coupons/generate-code', 'Api\CouponsController@generateCode');
    Route::get('coupons/search-user/{email}', 'Api\CouponsController@searchUser');
    Route::get('coupons/validate', 'Api\CouponsController@validateCode');
    
    // admin users
    Route::post('users/filter', 'Api\UsersController@filter');
    Route::get('users/{page?}/{term?},', 'Api\UsersController@index');

    Route::resource('products', 'Api\ProductsController')->except(['index', 'show','getProductData','filter','latest','searchByTerm']);
    Route::resource('categories', 'Api\CategoriesController')->except([ 'show','getCategories']);
    Route::resource('sales', 'Api\SalesController');
    Route::resource('newsletter', 'Api\NewsletterController')->except(['send']);
    Route::resource('coupons', 'Api\CouponsController')->except(['storeExternal']);
    Route::resource('contact', 'Api\ContactController');
    Route::resource('users', 'Api\UsersController');
    Route::resource('locations', 'Api\LocationsController')->except(['index']);
    Route::resource('payments', 'Api\PaymentController')->except(['mpIpnResponse','mpResponse']);
    
    
});

Route::group(['middleware' => 'guest:api'], function () {

    Route::post('login', 'Auth\LoginController@login');
    Route::post('register', 'Auth\RegisterController@register');

    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');

    Route::post('email/verify/{user}', 'Auth\VerificationController@verify')->name('verification.verify');
    Route::post('email/resend', 'Auth\VerificationController@resend');

    Route::post('oauth/{driver}', 'Auth\OAuthController@redirectToProvider');
    Route::get('oauth/{driver}/callback', 'Auth\OAuthController@handleProviderCallback')->name('oauth.callback');
});




