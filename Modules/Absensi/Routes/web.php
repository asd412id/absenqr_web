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

Route::prefix('absensi')->middleware('web','auth')->group(function() {
    Route::get('/', 'AbsensiController@index');

    Route::group(['prefix'=>'ruang'], function()
    {
      Route::get('/', 'RuangController@index')->name('absensi.ruang.index');
      Route::get('/tambah', 'RuangController@create')->name('absensi.ruang.create');
      Route::post('/tambah', 'RuangController@store')->name('absensi.ruang.store');
      Route::get('/{uuid}/ubah', 'RuangController@edit')->name('absensi.ruang.edit');
      Route::post('/{uuid}/ubah', 'RuangController@update')->name('absensi.ruang.update');
      Route::get('/{uuid}/hapus', 'RuangController@destroy')->name('absensi.ruang.destroy');
      Route::get('/{uuid}/export-pdf', 'RuangController@exportPDF')->name('absensi.ruang.export.pdf');
    });

    Route::group(['prefix'=>'jadwal'], function()
    {
      Route::get('/', 'JadwalController@index')->name('absensi.jadwal.index');
      Route::get('/tambah', 'JadwalController@create')->name('absensi.jadwal.create');
      Route::post('/tambah', 'JadwalController@store')->name('absensi.jadwal.store');
      Route::get('/{uuid}/ubah', 'JadwalController@edit')->name('absensi.jadwal.edit');
      Route::post('/{uuid}/ubah', 'JadwalController@update')->name('absensi.jadwal.update');
      Route::get('/{uuid}/hapus', 'JadwalController@destroy')->name('absensi.jadwal.destroy');
    });

    Route::group(['prefix'=>'user'], function()
    {
      Route::get('/', 'JadwalUserController@index')->name('absensi.jadwal.user.index');
      Route::get('/{uuid}/ubah', 'JadwalUserController@edit')->name('absensi.jadwal.user.edit');
      Route::post('/{uuid}/ubah', 'JadwalUserController@update')->name('absensi.jadwal.user.update');
      Route::get('/{uuid}/reset', 'JadwalUserController@reset')->name('absensi.jadwal.user.reset');
    });
});
