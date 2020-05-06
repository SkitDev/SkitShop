<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use TCG\Voyager\Facades\Voyager;

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

Route::get('/', 'ProductController@index')->name('shop');
Route::get('/search', 'ProductController@search')->name('products.search');

Route::group(['middleware' => ['auth']], function() {
    Route::get('/cart', 'CartController@index')->name('cart.index');
    Route::get('/cart/empty', 'CartController@empty')->name('cart.empty');
    Route::post('/cart/add', 'CartController@store')->name('cart.store');
    Route::delete('/cart/{rowId}', 'CartController@destroy')->name('cart.destroy');
    Route::patch('/cart/{rowId}', 'CartController@update')->name('cart.update');


    Route::get('/checkout', 'CheckoutController@index')->name('checkout.index');
    Route::post('/checkout', 'CheckoutController@store')->name('checkout.store');
    Route::get('/thanks', 'CheckoutController@thanks')->name('checkout.thanks');
});


Route::get('/product/{slug}', 'ProductController@show')->name('products.show');


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Auth::routes();

Route::get('/dashboard', 'HomeController@index')->name('dashboard');
