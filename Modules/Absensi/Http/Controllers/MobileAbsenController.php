<?php

namespace Modules\Absensi\Http\Controllers;

use App\Configs;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\Absensi\Entities\Ruang;

use Carbon\Carbon;

class MobileAbsenController extends Controller
{
    protected $configs;

    public function __construct()
    {
        $this->configs = Configs::getAll();
    }

    private function distance($lat1, $lng1, $lat2, $lng2)
    {
        // Convert degrees to radians
        $lat1 = deg2rad($lat1);
        $lng1 = deg2rad($lng1);
        $lat2 = deg2rad($lat2);
        $lng2 = deg2rad($lng2);

        // Calculate the distance using the Haversine formula
        $deltaLat = $lat2 - $lat1;
        $deltaLng = $lng2 - $lng1;
        $a = sin($deltaLat / 2) * sin($deltaLat / 2) + cos($lat1) * cos($lat2) * sin($deltaLng / 2) * sin($deltaLng / 2);
        $c = 2 * asin(sqrt($a));
        $distance = 6371000 * $c; // Earth's radius in meters

        return $distance;
    }

    private function withinRadius($lat1, $lng1, $lat2, $lng2, $radius)
    {
        $distance = $this->distance($lat1, $lng1, $lat2, $lng2);
        return $distance <= $radius;
    }

    public function absenCheck(Request $r)
    {
        $ruang_token = $r->header('Ruang-Token');
        $lat1 = $r->header('Latitude');
        $lng1 = $r->header('Longitude');

        $user = auth()->user();
        $ruang = Ruang::where('_token', $ruang_token)->first();

        $now = Carbon::now();
        $time = $now->format('H:i');
        $hari = $now->format('N');

        $getJadwal = $user->jadwal()
            ->where('hari', 'like', "%$hari%")
            ->where('cin', '>', $time)
            ->get();

        $jd = [];
        if (count($getJadwal)) {
            foreach ($getJadwal as $j) {
                $time5 = Carbon::createFromFormat("H:i", $j->cin)->subMinutes(5)->format("H:i:00");
                array_push($jd, [
                    'id' => $j->id,
                    'uuid' => $j->uuid,
                    'ruang' => $j->get_ruang->nama_ruang . ' (5 menit lagi)',
                    'name' => $j->nama_jadwal . ' - ' . $j->cin,
                    'date' => Carbon::now()->format("Y-m-d"),
                    'start_cin' => Carbon::createFromFormat("Y-m-d H:i:s", $now->format("Y-m-d ") . $time5)->timestamp * 1000,
                    'cin' => $j->cin,
                    'cout' => $j->cout,
                ]);
            }
        }

        if (@$this->configs->coordinate) {
            $coordinate = explode(",", @$this->configs->coordinate);
            $lat2 = trim(@$coordinate[0]);
            $lng2 = trim(@$coordinate[1]);
            $radius = @$this->configs->radius;

            if (count($coordinate) && $radius && !$this->withinRadius($lat1, $lng1, $lat2, $lng2, $radius)) {
                return response()->json([
                    'status' => 'error',
                    'jadwal' => $jd,
                    'message' => 'Anda berada di luar lokasi absensi!',
                ], 403);
            }
        }

        if (!$ruang) {
            return response()->json([
                'status' => 'error',
                'jadwal' => $jd,
                'message' => 'QR Code tidak terdaftar!',
            ], 404);
        }

        $jadwal = $user->jadwal()
            ->where('ruang', $ruang->id)
            ->where('hari', 'like', "%$hari%")
            ->where(function ($q) use ($time) {
                $q->where(function ($q) use ($time) {
                    $q->where('start_cin', '<=', $time)
                        ->where('end_cin', '>=', $time);
                })
                    ->orWhere(function ($q) use ($time) {
                        $q->where('start_cout', '<=', $time)
                            ->where('end_cout', '>=', $time);
                    });
            })
            ->count();

        if (!$jadwal) {
            return response()->json([
                'status' => 'error',
                'jadwal' => $jd,
                'message' => 'Jadwal tidak tersedia!',
            ], 404);
        }

        $user->absenRuang()->attach($ruang->id);
        $absen = $user->absen->last();

        return response()->json([
            'status' => 'success',
            'message' => 'Absen Berhasil',
            'ruang' => $ruang->nama_ruang,
            'jadwal' => $jd,
            'time' => $absen->created_at->format('H:i')
        ], 202);
    }
}
