<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::group([
    'prefix' => 'admin',
    'as' => 'admin.'
], function () {
    // Admin dashboard
    Route::view('/', 'layouts.admin')->middleware('web')->name('home');

    // Admin API routes
    Route::group([
        'middleware' => 'api',
        'namespace' => 'Api',
        'prefix' => 'api',
        'as' => 'api.'
    ], function () {
        Route::group([
            'namespace' => 'Auth'
        ], function () {
            Route::post('login', 'LoginController')->name('login');
            Route::post('refresh-token', 'RefreshTokenController')->name('refresh-token');
        });

        Route::group([
            'middleware' => 'auth:admin'
        ], function () {
            Route::post('logout', 'Auth\\LogoutController')->name('logout');

            Route::group(['prefix' => 'companies', 'namespace' => 'Companies'], function () {
                Route::get('/', 'IndexController')->name('companies');
                Route::get('show', 'ShowController')->name('fetch-company');

                Route::post('create', 'CreateController')->name('create-company');
                Route::post('update', 'UpdateController')->name('update-company');
                Route::post('cancel-delete', 'CancelDeleteController')->name('cancel-company-deletion');

                Route::delete('delete', 'DeleteController')->name('delete-company');
            });

            Route::group(['prefix' => 'chart', 'namespace' => 'Chart'], function () {
                Route::get('order', 'OrderController')->name('order-chart');
                Route::get('company-order', 'CompanyOrderController')->name('company-order-chart');
            });

            Route::group(['prefix' => 'dashboard', 'namespace' => 'Dashboard'], function () {
                Route::get('stats', 'StatsController')->name('dashboard-stats');
            });

            Route::group(['prefix' => 'carousel', 'namespace' => 'Carousel'], function () {
                Route::get('/', 'IndexController')->name('carousel-slides');

                Route::post('create', 'CreateController')->name('create-slide');
                Route::post('update', 'UpdateController')->name('update-slides');

                Route::delete('delete', 'DeleteController')->name('delete-slide');
            });

            Route::group(['prefix' => 'products', 'namespace' => 'Catalog'], function () {
                Route::get('/', 'IndexController')->name('products');

                Route::post('sync', 'SyncController')->name('sync-product');
                Route::post('reindex', 'ReindexController')->name('reindex-products');
            });

            Route::group(['prefix' => 'search-terms', 'namespace' => 'SearchTerms'], function () {
                Route::get('/', 'IndexController')->name('search-terms');

                Route::post('save', 'SaveController')->name('save-search-terms');

                Route::delete('delete', 'DeleteController')->name('delete-search-term');
            });

            Route::group(['prefix' => 'cms', 'namespace' => 'CMS'], function () {
                Route::get('blocks', 'BlocksController')->name('cms-get-blocks');

                Route::post('blocks/save', 'BlocksSaveController')->name('cms-save-block');
            });

            Route::group(['prefix' => 'packs', 'namespace' => 'Packs'], function () {
                Route::get('/', 'IndexController')->name('packs');

                Route::post('create', 'CreateController')->name('create-pack');
                Route::post('item/create', 'CreateItemController')->name('create-pack-item');

                Route::delete('delete', 'DeleteController')->name('delete-pack');
                Route::delete('item/delete', 'DeleteItemController')->name('delete-pack-item');
            });
        });
    });
});
