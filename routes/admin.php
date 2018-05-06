<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::group([
    'prefix' => 'admin',
    'as' => 'admin.'
], function () {
    // Admin guest routes
    Route::group([
        'middleware' => 'guest:admin',
    ], function () {
        Route::group(['as' => 'auth.', 'namespace' => 'Auth'], function () {
            Route::get('login', 'LoginController@getAction')->name('login');

            Route::post('login', 'LoginController@postAction');
        });
    });

    // Admin authed routes
    Route::group([
        'middleware' => 'auth:admin',
    ], function () {
        // Admin dashboard
        Route::get('/', 'DashboardController@getAction')->name('dashboard');

        // Logout route
        Route::post('logout', 'Auth\LogoutController@postAction')->name('auth.logout');

        // Admin API Routes
        Route::group(['as' => 'dashboard.', 'prefix' => 'api'], function () {
            Route::get('stats', 'DashboardController@stats')->name('stats');
            Route::get('chart/{type}', 'DashboardController@chart')->name('chart');
        });

        // Admin import page
        Route::get('import', 'ImportController@view')->name('import');

        Route::group(['as' => 'import.'], function () {
            Route::post('import/product', 'ImportController@product')->name('product');
            Route::post('import/image', 'ImportController@image')->name('image');
            Route::post('import/discount', 'ImportController@discount')->name('discount');
            Route::post('import/download', 'ImportController@download')->name('download');
        });

        Route::get('companies', 'Company\OverviewController@getAction')->name('companies');

        Route::group(['as' => 'company.', 'prefix' => 'company'], function () {
            Route::get('{id}', 'Company\DetailController@getAction')->name('edit');
        });

        // Admin user manager
        Route::group(['as' => 'user.', 'prefix' => 'user'], function () {
            Route::get('manager', 'UserController@view')->name('manager');
            Route::get('get', 'UserController@get')->name('get');
            Route::get('added', 'UserController@added')->name('added');

            Route::post('update', 'UserController@update')->name('update');
        });

        // Admin carousel manager
        Route::get('carousel', 'CarouselController@getAction')->name('carousel');

        Route::group(['as' => 'carousel.', 'prefix' => 'carousel'], function () {
            Route::delete('/', 'CarouselController@deleteAction')->name('delete');
            Route::patch('/', 'CarouselController@patchAction')->name('edit');
            Route::put('/', 'CarouselController@putAction')->name('create');
        });

        // Admin export
        Route::get('export', 'ExportController@view')->name('export');

        Route::group(['as' => 'export.', 'prefix' => 'export'], function () {
            Route::post('catalog', 'ExportController@catalog')->name('catalog');
            Route::post('pricelist', 'ExportController@pricelist')->name('pricelist');
        });

        // Admin content
        Route::get('content', 'ContentController@getAction')->name('content');

        Route::group(['as' => 'content.', 'prefix' => 'content', 'namespace' => 'Content'], function () {
            Route::post('description', 'DescriptionController@postAction')->name('description');
            Route::post('block', 'BlockController@postAction')->name('block');

            Route::put('description', 'DescriptionController@putAction');
            Route::put('block', 'BlockController@putAction');
        });

        // Admin packs
        Route::get('packs', 'PacksController@view')->name('packs');

        Route::group(['as' => 'packs.', 'prefix' => 'packs'], function () {
            Route::get('edit/{id}', 'PacksController@edit')->name('edit');

            Route::post('add', 'PacksController@create')->name('create');
            Route::post('addProduct', 'PacksController@addProduct')->name('add');
            Route::post('remove', 'PacksController@destroy')->name('delete');
            Route::post('removeProduct', 'PacksController@removeProduct')->name('remove');
        });

        // Admin cache
        Route::get('cache', 'CacheController@getAction')->name('cache');

        Route::group(['as' => 'cache.', 'prefix' => 'cache'], function () {
            Route::get('reset', 'CacheController@reset')->name('reset');
        });

        // Admin e-mail
        Route::get('email', 'EmailController@getAction')->name('email');

        Route::group(['as' => 'email.', 'prefix' => 'email'], function () {
            Route::get('stats', 'EmailController@stats')->name('stats');
            Route::post('test', 'EmailController@test')->name('test');
        });
    });
});
