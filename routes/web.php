<?php

/**
 * kode KN-01
 */
Route::get('/', ['uses' => 'Kode_knController@show', 'as' => 'home']);
Route::post('/', ['uses' => 'Kode_knController@getDataKodeKN']);

/**
 * WAREP
 */
Route::get('/warep', ['uses' => 'WarepController@show']);

Route::get('/export', ['uses' => 'ExportController@export', 'as' => 'export']);

/**
 * admin
 */
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function () {

    /**
     * /admin/
     */
    Route::get('/', ['uses' => 'AdminController@show', 'as' => 'admin.home']);

    /**
     * /admin/edit/user/{id}
     */
    Route::match(['get', 'post',], '/edit/user/{id}', ['uses' => 'UsersController@edit']);
//    Route::get( '/edit/user/{id}', ['uses' => 'UsersController@edit']);
//    Route::post('/edit/user/{id}', ['uses' => 'UsersController@edit']);

    /**
     * /admin/delete/user/{id}
     */
    Route::get('/delete/user/{id}', ['uses' => 'UsersController@delete']);
});

Route::auth();
