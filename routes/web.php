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

Route::group(['prefix' => 'admin48'], function () {
    // other routes
    Route::get('{any}', function ($any) {
        return File::get(public_path() . '/admin48/index.html');
    })->where('any', '.*');
});

Route::get('/',['as'=>'home','uses'=>'website\PageController@home']);
Route::get('download-app',['as' => 'download-app', 'uses' => 'common\PageController@getLink']);
Route::get('fq',['as' => 'fq-page', 'uses' => 'website\PageController@fq']);
Route::get('about',['as' => 'about-page', 'uses' => 'website\PageController@about']);
Route::get('orders-list',['as' => 'orders-list', 'uses' => 'website\PageController@orderList']);
Route::get('install-app',['as' => 'install-app', 'uses' => 'common\PageController@installApp']);
Route::get('notify',['as' => 'notify', 'uses' => 'website\NotifyController@notify']);
Route::get('product/{id}/{name}',['as' => 'get-product', 'uses' => 'website\ProductController@showPretty']);
Route::post('register',['as' => 'register', 'uses' => 'website\RegisterController@Register']);
Route::post('forget-pass',['as' => 'forget-pass', 'uses' => 'website\ForgetPassController@forgetPassword']);
Route::post('login',['as' => 'login', 'uses' => 'website\AuthController@login']);
Route::post('logout',['as' => 'logout', 'uses' => 'website\AuthController@logOutUser']);
Route::post('news-letter',['as' => 'news-letter', 'uses' => 'website\PageController@newsletter']);
Route::resource('product', 'website\ProductController');
Route::resource('blog', 'website\BlogController');
Route::group(['middleware' => 'auth'], function () {

    Route::group(array('prefix' => 'panel'), function () {

    Route::resource('account', 'website\panel\UserController');
    Route::resource('wallet', 'website\panel\WalletController');
    Route::resource('orders', 'website\panel\OrdersController');
    Route::resource('tickets', 'website\panel\TicketsController');
    Route::post('ticket-reply', ['as'=>'ticket-reply','uses'=>'website\panel\ReplyController@store']);
    Route::post('change-pass', ['as'=>'change-pass','uses'=>'website\AuthController@changePass']);
    Route::post('pay-pal-pay', ['as'=>'paypal-pay','uses'=>'website\panel\PaymentController@payWithpaypal']);
    Route::get('pay-pal-status', ['as'=>'paypal-status','uses'=>'website\panel\PaymentController@getPaymentStatus']);
});

});