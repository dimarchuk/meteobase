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
Route::post('/warep', ['uses' => 'WarepController@getDataWarep']);

/**
 * kode KN-01 daily
 */
Route::get('/kndaily', ['uses' => 'KNDailyController@show']);
Route::post('/kndaily', ['uses' => 'KNDailyController@getDataKodeKN']);

/**
 * kode KN-01 monthly
 */
//Route::get('/knmonthly', function () {
//    return view('site.knmonthly.kode_kn_monthly');
//});
Route::get('/knmonthly', ['uses' => 'KNMonthlyController@show']);
Route::post('/knmonthly', ['uses' => 'KNMonthlyController@getDataKodeKN']);

/**
 * For Export to exel
 */
Route::get('/export/{group?}', ['uses' => 'ExportController@export', 'as' => 'export']);

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

    /**
     * /admin/delete/user/{id}
     */
    Route::get('/delete/user/{id}', ['uses' => 'UsersController@delete']);
});

Route::auth();
