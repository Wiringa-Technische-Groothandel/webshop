<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

$routeOptions = [
    'middleware' => 'can:visit admin panel',
    'prefix' => 'admin',
    'as' => 'admin.'
];

Route::group($routeOptions, function () {
    // Admin dashboard
    Route::get('/', 'DashboardController@getAction')->name('dashboard');

    // Admin API Routes
    Route::group(['as' => 'dashboard::', 'prefix' => 'api'], function () {
        Route::get('stats', 'DashboardController@stats')->name('stats');
        Route::get('chart/{type}', 'DashboardController@chart')->name('chart');
    });

    // Admin import page
    Route::get('import', 'ImportController@view')->name('import');

    Route::group(['as' => 'import::'], function () {
        Route::post('import/product', 'ImportController@product')->name('product');
        Route::post('import/image', 'ImportController@image')->name('image');
        Route::post('import/discount', 'ImportController@discount')->name('discount');
        Route::post('import/download', 'ImportController@download')->name('download');
    });

    Route::get('company', 'CompanyManagerController@getAction')->name('company-manager');

    // Admin user manager
    Route::group(['as' => 'user::', 'prefix' => 'user'], function () {
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

    Route::group(['as' => 'export::', 'prefix' => 'export'], function () {
        Route::post('catalog', 'ExportController@catalog')->name('catalog');
        Route::post('pricelist', 'ExportController@pricelist')->name('pricelist');
    });

    // Admin content
    Route::get('content', 'ContentController@view')->name('content');

    Route::group(['as' => 'content::', 'prefix' => 'content'], function () {
        Route::get('get', 'ContentController@content')->name('content');
        Route::get('description', 'ContentController@description')->name('description');
        Route::post('save/page', 'ContentController@savePage')->name('save_page');
        Route::post('save/description', 'ContentController@saveDescription')->name('save_description');
    });

    // Admin packs
    Route::get('packs', 'PacksController@view')->name('packs');

    Route::group(['as' => 'packs::', 'prefix' => 'packs'], function () {
        Route::get('edit/{id}', 'PacksController@edit')->name('edit');

        Route::post('add', 'PacksController@create')->name('create');
        Route::post('addProduct', 'PacksController@addProduct')->name('add');
        Route::post('remove', 'PacksController@destroy')->name('delete');
        Route::post('removeProduct', 'PacksController@removeProduct')->name('remove');
    });

    // Admin cache
    Route::get('cache', 'CacheController@getAction')->name('cache');

    Route::group(['as' => 'cache::', 'prefix' => 'cache'], function () {
        Route::get('reset', 'CacheController@reset')->name('reset');
    });

    // Admin e-mail
    Route::get('email', 'EmailController@getAction')->name('email');

    Route::group(['as' => 'email::', 'prefix' => 'email'], function () {
        Route::get('stats', 'EmailController@stats')->name('stats');
        Route::post('test', 'EmailController@test')->name('test');
    });
});
