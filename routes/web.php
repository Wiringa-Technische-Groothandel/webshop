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

Route::get('/', 'IndexController@getAction')->name('home');
Route::get('downloads', 'DownloadsController@getAction')->name('downloads');

Route::group([
    'as' => 'auth.',
    'namespace' => 'Auth'
], function () {
    Route::get('login', 'LoginController@getAction')->name('login');
    Route::get('register', 'RegistrationController@getAction')->name('register');

    Route::post('login', 'LoginController@postAction');
    Route::post('logout', 'LogoutController@postAction')->name('logout');

    Route::put('register', 'RegistrationController@putAction');
});

Route::group([
    'as' => 'catalog.',
    'namespace' => 'Catalog',
], function () {
    Route::get('product/{sku}', 'ProductController@getAction')->name('product');
    Route::get('search', 'SearchController@getAction')->name('search');
    Route::get('assortment', 'AssortmentController@getAction')->name('assortment');

    Route::post('search', 'SearchController@postAction');

    Route::group([
        'middleware' => ['auth']
    ], function () {
        Route::get('product/{sku}/price', 'PriceController@getAction');

        Route::post('fetchPrices', 'PriceController@postAction')->name('fetchPrices');
    });
});

Route::group([
    'as' => 'favorites.',
    'prefix' => 'favorites',
    'namespace' => 'Favorites',
    'middleware' => ['auth']
], function () {
    Route::post('/', 'IndexController@postAction')->name('check');

    Route::patch('/', 'IndexController@patchAction')->name('toggle');

    Route::delete('/', 'IndexController@deleteAction')->name('delete');
});

Route::group([
    'as' => 'checkout.',
    'prefix' => 'checkout',
    'namespace' =>  'Checkout',
    'middleware' => ['auth']
], function () {
    Route::get('cart', 'CartController@getAction')->name('cart');
    Route::get('cart/items', 'Cart\ItemsController@getAction')->name('cart.items');
    Route::get('address', 'AddressController@getAction')->name('address');
    Route::get('finished', 'FinishController@getAction')->name('finished');

    Route::post('finish', 'FinishController@postAction')->name('finish');

    Route::put('cart', 'CartController@putAction');

    Route::patch('cart', 'CartController@patchAction');
    Route::patch('address', 'AddressController@patchAction');

    Route::delete('cart', 'CartController@deleteAction')->name('cart.destroy');
    Route::delete('cart/product/{sku}', 'CartController@deleteAction');
});

Route::group([
    'as' => 'account.',
    'prefix' => 'account',
    'namespace' => 'Account',
    'middleware' => ['auth']
], function () {
    Route::get('/', 'DashboardController@getAction')->name('dashboard');
    Route::get('accounts', 'SubAccountController@getAction')->name('sub_accounts');
    Route::get('password', 'PasswordController@getAction')->name('change_password');
    Route::get('favorites', 'FavoritesController@getAction')->name('favorites');
    Route::get('order-history', 'OrderHistoryController@getAction')->name('order-history');
    Route::get('addresses', 'AddressController@getAction')->name('addresses');
    Route::get('discount', 'DiscountController@getAction')->name('discount');

    Route::post('password', 'PasswordController@postAction');
    Route::post('order-history', 'OrderHistoryController@postAction');
    Route::post('discount', 'DiscountController@postAction');

    Route::put('accounts', 'SubAccountController@putAction');
    Route::put('addresses', 'AddressController@putAction');
    Route::put('favorites/cart', 'FavoritesController@putAction')->name('favorites.addToCart');

    Route::patch('addresses', 'AddressController@patchAction');

//    Route::group([
//        'prefix' => 'accounts',
//        'as' => 'accounts.'
//    ], function () {
//        Route::post('/', 'SubAccountController@store');
//        Route::post('update/{id}', 'SubAccountController@update')->name('update');
//        Route::post('remove', 'SubAccountController@destroy')->name('delete');
//    });
//
//
//    Route::group([
//        'prefix' => 'orders',
//        'as' => 'orders.'
//    ], function () {
//        Route::get('/', 'OrderHistoryController@view')->name('view');
//
//        Route::get('{order}', 'OrderHistoryController@addOrderToCart')->name('reorder');
//    });
//
//    Route::group([
//        'prefix' => 'addresses',
//        'as' => 'addresses.'
//    ], function () {
//        Route::get('/', 'AddressController@view')->name('view');
//
//        Route::post('add', 'AddressController@add')->name('add');
//        Route::post('delete/{id}', 'AddressController@delete')->name('delete');
//    });
//
//    Route::group([
//        'prefix' => 'discountfile',
//        'as' => 'discountfile.'
//    ], function () {
//        Route::get('/', 'DiscountfileController@view')->name('view');
//        Route::get('generate/{type}/{method}', 'DiscountfileController@generate')->name('generate');
//    });
});