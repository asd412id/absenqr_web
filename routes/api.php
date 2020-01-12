<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('api')->prefix('v1')->group(function () {
    Route::get('/check-server','MobileController@checkServer');
    Route::post('/login','MobileController@login');

    Route::middleware('auth:api')->group(function(){
      Route::put('/activate','MobileController@activate');
      Route::put('/change-password','MobileController@changePassword');

      Route::middleware('mobile.auth')->group(function(){
        Route::get('/user',function(){
          return response()->json([
            'status'=>'success',
            'data'=>auth()->user()
          ]);
        });
        Route::get('/keluar','MobileController@logout');
      });
    });
});
