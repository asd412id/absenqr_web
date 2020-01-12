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

Route::prefix('arsip')->middleware('web','auth','roles:admin,pegawai')->group(function() {

  Route::get('/', 'ArsipController@index')->name('arsip.index');

  Route::prefix('siswa')->group(function(){
    Route::get('/','SiswaController@index')->name('siswa.index');
    Route::get('/ekspor-pdf','SiswaController@exportPDF')->name('siswa.export.pdf');

    Route::middleware('roles:admin')->group(function()
    {
      Route::get('/tambah','SiswaController@create')->name('siswa.create');
      Route::post('/impor','SiswaController@importExcel')->name('siswa.import.excel');
      Route::get('/download-template-excel','SiswaController@downloadTemplateExcel')->name('siswa.download.template.excel');
      Route::post('/simpan','SiswaController@store')->name('siswa.store');
      Route::get('/{uuid}/reset-login','SiswaController@resetLogin')->name('siswa.reset.login');
      Route::get('/{uuid}/ubah','SiswaController@edit')->name('siswa.edit');
      Route::post('/{uuid}/update','SiswaController@update')->name('siswa.update');
      Route::get('/{uuid}/hapus','SiswaController@destroy')->name('siswa.destroy');
    });

    Route::get('/{uuid}','SiswaController@show')->name('siswa.show');
    Route::get('/{uuid}/ekspor-pdf','SiswaController@exportSinglePDF')->name('siswa.export.single.pdf');
  });

  Route::prefix('pegawai')->group(function(){
    Route::get('/','PegawaiController@index')->name('pegawai.index');
    Route::get('/ekspor-pdf','PegawaiController@exportPDF')->name('pegawai.export.pdf');

    Route::middleware('roles:admin')->group(function(){
      Route::get('/tambah','PegawaiController@create')->name('pegawai.create');
      Route::post('/simpan','PegawaiController@store')->name('pegawai.store');
      Route::get('/{uuid}/reset-login','PegawaiController@resetLogin')->name('pegawai.reset.login');
      Route::get('/{uuid}/ubah','PegawaiController@edit')->name('pegawai.edit');
      Route::post('/{uuid}/update','PegawaiController@update')->name('pegawai.update');
      Route::get('/{uuid}/hapus','PegawaiController@destroy')->name('pegawai.destroy');
    });

    Route::get('/{uuid}','PegawaiController@show')->name('pegawai.show');
    Route::get('/{uuid}/ekspor-pdf','PegawaiController@exportSinglePDF')->name('pegawai.export.single.pdf');
  });

});
