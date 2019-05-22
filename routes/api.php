<?php

Route::get('product/{sku}', 'ProductController@getAction')->name('product');