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

Route::prefix('payroll')->middleware('auth')->group(function() {
    Route::get('/', 'PayrollController@index')->name('payroll.log.index');

    Route::group(['prefix'=>'user'], function()
    {
      Route::get('/', 'PayrollUserController@index')->name('payroll.user.index');
      Route::get('/tambah', 'PayrollUserController@create')->name('payroll.user.create');
      Route::get('/tambah/{uuid}', 'PayrollUserController@create')->name('payroll.user.createbyuser');
      Route::post('/tambah', 'PayrollUserController@store')->name('payroll.user.store');
      Route::get('/{uuid}', 'PayrollUserController@show')->name('payroll.user.show');
      Route::get('/{uuid}/reset', 'PayrollUserController@reset')->name('payroll.user.reset');
      Route::get('/{uuid}/edit', 'PayrollUserController@edit')->name('payroll.user.edit');
      Route::post('/{uuid}/edit', 'PayrollUserController@update')->name('payroll.user.update');
      Route::get('/{uuid}/destroy', 'PayrollUserController@destroy')->name('payroll.user.destroy');
    });
});
