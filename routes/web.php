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

Route::middleware('web')->group(function(){
  Route::get('/app','MainController@downloadAPP');

  Route::group(['middleware'=>'guest'], function()
  {
    Route::get('/', 'MainController@login')->name('login');
    Route::post('masuk', 'MainController@loginProcess')->name('login.process');
  });

  Route::group(['middleware'=>'auth'], function()
  {
    Route::get('keluar', 'MainController@logout')->name('logout');

    Route::get('pengaturan', 'MainController@sysConf')->name('configs');
    Route::post('pengaturan', 'MainController@sysConfUpdate')->name('configs.update');

    Route::get('/pengaturan/hapus-img/{img}', 'MainController@deleteImg')->name('configs.delete.img');

    Route::group(['prefix'=>'ajax'],function(){
      Route::get('user', 'AjaxController@searchUser')->name('ajax.search.user');
      Route::get('pegawai', 'AjaxController@searchPegawai')->name('ajax.search.pegawai');
      Route::get('ruang', 'AjaxController@searchRuang')->name('ajax.search.ruang');
      Route::get('jadwal', 'AjaxController@searchJadwal')->name('ajax.search.jadwal');
    });

    Route::get('pengaturan/akun', 'MainController@profile')->name('profile');
    Route::post('pengaturan/akun', 'MainController@profileUpdate')->name('profile.update');
  });
});
