<?php

namespace WTG\Http\Controllers\Api;

use Illuminate\Support\Facades\Route;
use Laravel\Passport\Passport;

Passport::routes(
    null,
    [
        'prefix' => 'auth'
    ]
);

Route::get('routes', Routes\IndexController::class)->name('routes');

Route::get('cms/block', Cms\BlockController::class)->name('cms.block');

Route::get('carousel/items', Carousel\IndexController::class)->name('carousel.items');

Route::group(
    ['middleware' => 'auth:api'],
    function () {
        Route::get('auth/me', Auth\UserController::class)->name('auth.me');
    }
);
