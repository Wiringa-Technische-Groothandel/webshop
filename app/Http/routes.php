<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// GET Requests will be handled here
Route::get('/', 'HomeController@home');                                         // Homepage
Route::get('about', 'HomeController@about');                                       // About us
Route::get('contact', 'HomeController@contact');                                 // Contact
Route::get('downloads', 'HomeController@downloads');                             // Downloads
Route::get('licenses', 'HomeController@licenses');                              // Licenses
Route::get('assortment', 'HomeController@assortment');                          // Assortment
Route::get('vacancies', 'HomeController@vacancies');
Route::get('download/{filename}', 'HomeController@download');                   // Download a file

Route::group(['middleware' => 'guest'], function () {
    Route::get('password/email', 'Auth\PasswordController@getEmail');            // Forgot password page
    Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');    // Password reset page
    Route::get('register', 'UserController@register');                            // Show the registration page
    Route::get('register/sent', 'UserController@registerSent');                    // Show the registration success page

    Route::post('login', 'UserController@login');                               // Login handler
    Route::post('register', 'UserController@register_check');                    // Validate the registration reguest
    Route::post('password/email', 'Auth\PasswordController@postEmail');            // Reset password handler
    Route::post('password/reset', 'Auth\PasswordController@postReset');
});

/*
 * Webshop routes
 */
Route::get('webshop', 'WebshopController@main');                                // Main webshop page
Route::get('product/{product}', 'ProductController@showProduct');            // Product page
Route::get('search', 'SearchController@search');                                   // Page with the search results
Route::get('specials', 'SearchController@specials');                               // Show only the specials
Route::get('clearance', 'SearchController@clearance');                             // Show only the clearance products

Route::group(['middleware' => 'auth.admin'], function () {
    Route::get('phpinfo', 'Admin\AdminController@phpinfo');                     // Display the phpinfo stuff

    Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
        Route::get('/', 'AdminController@overview');                            // Admin overview
        Route::get('managecontent', 'AdminController@contentManager');            // Content manager
        Route::get('generate', 'AdminController@generate');                        // Generate page
        Route::get('carousel', 'AdminController@carousel');                        // Carousel manager
        Route::get('removeCarouselSlide/{id}', 'AdminController@removeSlide');    // Try to remove a carousel slide
        Route::get('usermanager', 'AdminController@userManager');                // Simple user manager
        Route::get('userAdded', 'AdminController@userAdded');                    // The user added page

        Route::post('saveContent', 'AdminController@saveContent');               // Save the page content
        Route::post('catalog', 'AdminController@generateCatalog');                // Generate the catalog
        Route::post('addCarouselSlide', 'AdminController@addSlide');            // Try to add a carousel slide
        Route::post('editCarouselSlide/{id}', 'AdminController@editSlide');        // Edit the slide order
        Route::post('updateCompany', 'AdminController@updateCompany');          // Update/add the user
        Route::post('updateDescription', 'AdminController@updateDescription')->name('update_description');
        Route::post('pricelist', [
            'middleware' => 'RemoveTempFile',                                   // Middleware to remove the temp csv file after download
            'uses'       => 'AdminController@generate_pricelist',                      // Generate a downloadable pricelist for a specified user
        ]);

        Route::group(['prefix' => 'import'], function () {
            Route::get('/', 'AdminController@import');                          // The page where the user can upload a CSV file with the products
            Route::get('success', 'AdminController@importSuccess');             // Import success page

            Route::post('product', 'ImportController@product');                   // Handle the product import
            Route::post('discount', 'ImportController@discount');                 // Handle the discount import
            Route::post('image', 'ImportController@image');                     // Handle the image import
            Route::post('download', 'ImportController@download');                 // Handle the download file import
        });

        Route::group(['prefix' => 'api', 'middleware' => 'ajax'], function () {
            Route::get('cpu', 'ApiController@cpu');                      // Get CPU Load
            Route::get('ram', 'ApiController@ram');                      // Get RAM Load
            Route::get('chart/{type}', 'ApiController@chart');           // Get data for a chart
            Route::get('product/{product}', 'ApiController@product');    // Get product data
            Route::get('company', 'ApiController@companyDetails');       // Get user details
            Route::get('content', 'ApiController@content');                // Get the content for a field
            Route::get('description', 'ApiController@description');
        });

        Route::group(['prefix' => 'packs'], function () {
            Route::get('/', 'PacksController@index');
            Route::get('edit/{id}', 'PacksController@edit');

            Route::post('add', 'PacksController@create');
            Route::post('addProduct', 'PacksController@addProduct');
            Route::post('remove', 'PacksController@destroy');
            Route::post('removeProduct', 'PacksController@removeProduct');
        });

        Route::group(['prefix' => 'cache'], function () {
            Route::get('/', 'CacheController@stats');                         // Get information about cache usage

            Route::post('reset', 'CacheController@reset');                    // Reset the OpCache cache
        });
    });
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('logout', 'UserController@logout');                              // Logout the current user
    Route::get('reorder/{order_id}', 'WebshopController@reorder');                // Add the items from a previous order to the cart again

    Route::group(['prefix' => 'cart'], function () {
        Route::get('/', 'CartController@view');                                   // Show the cart
        Route::get('destroy', 'CartController@destroy');                        // Remove all items from the cart
        Route::get('order/finished', 'WebshopController@orderFinished');        // Show the order finished page

        Route::post('add', 'CartController@addProduct');                        // Add product to cart
        Route::post('update', 'CartController@update');                            // Update or remove product from cart
        Route::post('order', 'CartController@order');                            // Send the order
    });

    Route::group(['prefix' => 'account'], function () {
        Route::get('/', 'AccountController@overview');                          // Account overview
        Route::get('favorites', 'AccountController@favorites');                 // Favorites
        Route::get('orderhistory', 'AccountController@orderHistory');           // Order history
        Route::get('addresslist', 'AccountController@addressList');             // Addresslist
        Route::get('discountfile', 'AccountController@discountFile');           // ICC/CSV Discount generation page
        Route::get('generate_{type}/{method}', [
            'middleware' => 'RemoveTempFile',                                    // Middleware to remove the temp csv/icc file after download
            'uses'       => 'AccountController@generateFile',                            // Discount file generation handler
        ]);

        /*
         * Account routes
         */
        Route::group(['namespace' => 'Account'], function () {
            Route::group(['prefix' => 'accounts', 'middleware' => 'manager'], function () {
                Route::get('/', 'SubAccountController@index');

                Route::post('/', 'SubAccountController@store');
                Route::post('update/{id}', 'SubAccountController@update')->name('update_subaccount');
                Route::post('remove', 'SubAccountController@destroy')->name('delete_subaccount');
            });

            // Change password page
            Route::get('password', 'PasswordController@getChangePassword')->name('change_password');

            // Handle the change password request
            Route::post('password', 'PasswordController@doChangePassword');
        });

        Route::post('addAddress', 'AccountController@addAddress');                 // Add address to the database
        Route::post('removeAddress', 'AccountController@removeAddress');        // Remove address from the database
        Route::post('modFav', 'AccountController@modFav');                         // Change the favorites
        Route::post('isFav', 'AccountController@isFav');                           // Check the product array
    });
});

// POST Requests will be handled here
// TODO: Change to something that is not deprecated
Route::when('*', 'csrf', ['post', 'put', 'delete']);
