<?php

use App\Configs;
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
                $configs = Configs::getAll();
                $now = Carbon::now()->addMinutes(@$configs->minute_alarm ?? 5);
                $time = $now->format('H:i');
                $hari = $now->format('N');

                $getJadwal = auth()->user()->jadwal()
                    ->where('hari', 'like', "%$hari%")
                    ->where('cin', '>', $time)
                    ->get();

                $jd = [];

                if (count($getJadwal)) {
                    foreach ($getJadwal as $j) {
                        $time5 = Carbon::createFromFormat('H:i', $j->cin)->subMinutes(@$configs->minute_alarm ?? 5)->format("Y-m-d H:i:s");
                        array_push($jd, [
                            'id' => $j->id,
                            'uuid' => $j->uuid,
                            'ruang' => $j->get_ruang->nama_ruang . ' (' . (@$configs->minute_alarm ?? 5) . ' menit lagi)',
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
                    'jadwals_today' =>  auth()->user()->jadwal()->with('get_ruang')->select('get_ruang.nama_ruang as ruang', 'name', 'cin', 'cout', 'hari')->where('hari', 'like', "%$hari%")->orderBy('cin', 'asc')->get(),
                    'jadwals' => $jd
                ]);
            });
            Route::get('/keluar', 'MobileController@logout');
        });
    });
});
