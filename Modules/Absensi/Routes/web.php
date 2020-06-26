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
    Route::get('/', 'AbsensiController@index')->name('absensi.index');

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
      Route::get('/tambah/{uuid}', 'JadwalController@create')->name('absensi.jadwal.copy');
      Route::get('/tambah/user/{uuid}', 'JadwalController@createByUser')->name('absensi.jadwal.byuser');

      Route::get('/tambah', 'JadwalController@create')->name('absensi.jadwal.create');
      Route::post('/tambah', 'JadwalController@store')->name('absensi.jadwal.store');
      Route::get('/{uuid}/ubah', 'JadwalController@edit')->name('absensi.jadwal.edit');
      Route::post('/{uuid}/ubah', 'JadwalController@update')->name('absensi.jadwal.update');
      Route::get('/{uuid}/hapus', 'JadwalController@destroy')->name('absensi.jadwal.destroy');
    });

    Route::group(['prefix'=>'hari-libur'], function()
    {
      Route::get('/', 'HariLiburController@index')->name('absensi.libur.index');
      Route::get('/tambah', 'HariLiburController@create')->name('absensi.libur.create');
      Route::post('/tambah', 'HariLiburController@store')->name('absensi.libur.store');
      Route::get('/{uuid}/ubah', 'HariLiburController@edit')->name('absensi.libur.edit');
      Route::post('/{uuid}/ubah', 'HariLiburController@update')->name('absensi.libur.update');
      Route::get('/{uuid}/hapus', 'HariLiburController@destroy')->name('absensi.libur.destroy');
    });

    Route::group(['prefix'=>'user'], function()
    {
      Route::get('/', 'JadwalUserController@index')->name('absensi.jadwal.user.index');
      Route::get('/{uuid}/ubah', 'JadwalUserController@edit')->name('absensi.jadwal.user.edit');
      Route::post('/{uuid}/ubah', 'JadwalUserController@update')->name('absensi.jadwal.user.update');
      Route::get('/{uuid}/reset', 'JadwalUserController@reset')->name('absensi.jadwal.user.reset');
    });

    Route::group(['prefix'=>'keterangan'], function()
    {
      Route::get('/', 'AbsensiDescController@index')->name('absensi.desc.index');
      Route::get('/tambah', 'AbsensiDescController@create')->name('absensi.desc.create');
      Route::post('/tambah', 'AbsensiDescController@store')->name('absensi.desc.store');
      Route::get('/{uuid}/ubah', 'AbsensiDescController@edit')->name('absensi.desc.edit');
      Route::post('/{uuid}/ubah', 'AbsensiDescController@update')->name('absensi.desc.update');
      Route::get('/{uuid}/hapus', 'AbsensiDescController@destroy')->name('absensi.desc.destroy');
    });

    Route::group(['prefix'=>'log'], function()
    {
      Route::get('/', 'AbsensiLogController@index')->name('absensi.log.index');
      Route::post('/', 'AbsensiLogController@showLogs')->name('absensi.log.show');
    });
});
