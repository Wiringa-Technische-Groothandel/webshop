<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::group(
    [
        'prefix' => 'api',
        'as'     => 'api.'
    ],
    function () {
        Route::group(
            [
                'namespace' => 'Auth',
                'prefix' => 'auth',
                'as' => 'auth.'
            ],
            function () {
                Route::post('login', 'LoginController')->middleware('guest')->name('login');
                Route::post('logout', 'LogoutController')->middleware('auth:airlock-web')->name('logout');
            }
        );

        Route::group(
            [
                'namespace' => 'CMS',
                'prefix' => 'cms',
                'as' => 'cms.'
            ],
            function () {
                Route::get('block', 'BlockController')->name('block');
            }
        );

        Route::group(
            [
                'namespace' => 'Catalog',
                'prefix' => 'catalog',
                'as' => 'catalog.'
            ],
            function () {
                Route::get('products', 'ProductsController')->name('products');
                Route::get('price', 'PriceController')->middleware('auth:airlock-web')->name('price');
            }
        );
    }
);
