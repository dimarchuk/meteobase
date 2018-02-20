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

Route::get('/', ['uses' => 'Kode_knController@show', 'as' => 'home']);

Route::post('/', ['uses' => 'Kode_knController@ajaxShow']);

Route::auth();
//Auth::routes();

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function () {

    Route::get('/', function () {
        echo "it`s admin";
        dd(Auth::user()->id);
    });
});
