<?php

use Carbon\Carbon;
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
    Route::get('/check-server', 'MobileController@checkServer');
    Route::post('/login', 'MobileController@login');

    Route::middleware('auth:api')->group(function () {
        Route::put('/activate', 'MobileController@activate');
        Route::put('/change-password', 'MobileController@changePassword');

        Route::middleware('mobile.auth')->group(function () {
            Route::get('/user', function () {
                $now = Carbon::now()->addMinutes(@$this->configs->minute_alarm ?? 5);
                $time = $now->format('H:i');
                $hari = $now->format('N');

                $getJadwal = auth()->user()->jadwal()
                    ->where('hari', 'like', "%$hari%")
                    ->where('cin', '>', $time)
                    ->get();

                $jd = [];

                if (count($getJadwal)) {
                    foreach ($getJadwal as $j) {
                        $time5 = Carbon::createFromFormat('H:i', $j->cin)->subMinutes(@$this->configs->minute_alarm ?? 5)->format("Y-m-d H:i:s");
                        array_push($jd, [
                            'id' => $j->id,
                            'uuid' => $j->uuid,
                            'ruang' => $j->get_ruang->nama_ruang . ' (' . (@$this->configs->minute_alarm ?? 5) . ' menit lagi)',
                            'name' => $j->nama_jadwal . ' - ' . $j->cin,
                            'date' => Carbon::now()->format("Y-m-d"),
                            'start_cin' => $time5,
                            'cin' => $j->cin,
                            'cout' => $j->cout,
                        ]);
                    }
                }
                return response()->json([
                    'status' => 'success',
                    'data' => auth()->user(),
                    'date' => $now->format('d/m/Y'),
                    'time' => $now->format('H:i'),
                    'jadwals' => $jd
                ]);
            });
            Route::get('/keluar', 'MobileController@logout');
        });
    });
});
