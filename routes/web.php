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
    Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');

    Route::post('login', 'LoginController@postAction');
    Route::post('logout', 'LogoutController@postAction')->name('logout');
    Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::post('password/reset', 'ResetPasswordController@reset')->name('password.resetPost');

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
    'prefix' => 'customer',
    'namespace' => 'Account',
    'middleware' => ['auth']
], function () {
    Route::get('/', 'ProfileController@getAction')->name('profile');
    Route::get('edit', 'ProfileController@editAction')->name('profile.edit');
    Route::get('accounts', 'SubAccountController@getAction')->name('sub_accounts');
    Route::get('password', 'PasswordController@getAction')->name('change_password');
    Route::get('favorites', 'FavoritesController@getAction')->name('favorites');
    Route::get('order-history', 'OrderHistoryController@getAction')->name('order-history');
    Route::get('order/{uuid}', 'OrderController@getAction')->name('order.view');
    Route::get('invoices', 'InvoiceController@getAction')->name('invoices');
    Route::get('invoices/{file}', 'InvoiceController@viewAction')->name('invoices.view');
    Route::get('addresses', 'AddressController@getAction')->name('addresses');
    Route::get('discount', 'DiscountController@getAction')->name('discount');

    Route::post('edit', 'ProfileController@postAction');
    Route::post('password', 'PasswordController@postAction');
    Route::post('order-history', 'OrderHistoryController@postAction');
    Route::post('order/{uuid}', 'OrderController@postAction');
    Route::post('update-role', 'SubAccountController@postAction')->name('update-role');
    Route::post('discount', 'DiscountController@postAction');

    Route::put('accounts', 'SubAccountController@putAction');
    Route::put('addresses', 'AddressController@putAction');
    Route::put('favorites/cart', 'FavoritesController@putAction')->name('favorites.addToCart');

    Route::patch('addresses', 'AddressController@patchAction');

    Route::delete('accounts/{account}', 'SubAccountController@deleteAction')->name('remove');
});