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
                $time = $now->copy()->format('H:i');
                $hari = $now->copy()->format('N');
                $besok = $now->copy()->tomorrow()->format('N');

                $jd = collect(auth()->user()->jadwal()
                    ->where('hari', 'like', "%$hari%")
                    ->where('cin', '>', $time)
                    ->get())->transform(function ($j) use ($configs) {
                    $time5 = Carbon::createFromFormat('H:i', $j->cin)->subMinutes(@$configs->minute_alarm ?? 5)->format("Y-m-d H:i:s");
                    return [
                        'id' => $j->id,
                        'uuid' => $j->uuid,
                        'ruang' => $j->get_ruang->nama_ruang . ' (' . (@$configs->minute_alarm ?? 5) . ' menit lagi)',
                        'name' => $j->nama_jadwal . ' - ' . $j->cin,
                        'date' => Carbon::now()->format("Y-m-d"),
                        'start_cin' => $time5,
                        'cin' => $j->cin,
                        'cout' => $j->cout,
                    ];
                });

                $jadwalsTomorrow = collect(auth()->user()
                    ->jadwal()
                    ->with('get_ruang')
                    ->has('get_ruang')
                    ->where('hari', 'like', "%$besok%")
                    ->orderBy('cin', 'asc')
                    ->get())->transform(function ($j) {
                    return [
                        "nama_jadwal" => $j->nama_jadwal,
                        "cin" => $j->cin,
                        "cout" => $j->cout,
                        "get_ruang" => [
                            "nama_ruang" => $j->get_ruang->nama_ruang . " (Besok)"
                        ]
                    ];
                });

                $jadwals = collect(auth()->user()
                    ->jadwal()
                    ->with('get_ruang')
                    ->has('get_ruang')
                    ->where('hari', 'like', "%$hari%")
                    ->orderBy('cin', 'asc')
                    ->get())->transform(function ($j) {
                    return [
                        "nama_jadwal" => $j->nama_jadwal,
                        "cin" => $j->cin,
                        "cout" => $j->cout,
                        "get_ruang" => [
                            "nama_ruang" => $j->get_ruang->nama_ruang
                        ]
                    ];
                })->merge($jadwalsTomorrow);

                return response()->json([
                    'status' => 'success',
                    'data' => auth()->user(),
                    'date' => $now->format('d/m/Y'),
                    'time' => $now->format('H:i'),
                    'jadwals_today' =>  $jadwals,
                    'jadwals' => $jd
                ]);
            });
            Route::get('/keluar', 'MobileController@logout');
        });
    });
});
