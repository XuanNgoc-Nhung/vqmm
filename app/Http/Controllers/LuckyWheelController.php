<?php

namespace App\Http\Controllers;

use App\lichSuTrungQua;
use App\phanThuong;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class LuckyWheelController extends Controller
{
    /**
     * Get view home
     *
     * @return view
     */
    public function index()
    {
        return view('luckywheel');
    }

    /**
     * get all gifts
     *
     * @return json
     */
    public function gifts()
    {
        return response()->json(phanThuong::orderByDesc('created_at')->get());
    }

    /**
     * Get spin
     *
     * @param string $phone
     *
     * @return json
     */
    public function getSpin($phone)
    {
        try {
            $now = Carbon::now()->format('Y-m-d');
            $phoneCheck = lichSuTrungQua::wherePhone($phone)->whereDate('created_at', $now)->exists();

            return response()->json(['status' => !$phoneCheck]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * get result
     *
     * @param string $phone
     *
     * @return json
     */
    public function result($phone)
    {
        try {
            $now = Carbon::now()->format('Y-m-d');
            $phoneCheck = lichSuTrungQua::wherePhone($phone)->whereDate('created_at', $now)->exists();

            if (!$phoneCheck) {
                $gifts = phanThuong::whereRaw('used < amount')->get();
                $percentTotal = $gifts->sum('ratio');
                if ($percentTotal > 100) {
                    return response()->json(['error' => 'Lỗi hệ thống'], 500);
                } else {
                    $sampleSpace = [];
                    foreach ($gifts as $key => $gift) {
                        if ($gift->ratio > 0) {
                            for ($i = 0; $i < $gift->ratio; $i++) {
                                array_push($sampleSpace, $gift->id);
                            }
                        }
                    }

                    $idPhanThuong = $sampleSpace[array_rand($sampleSpace)];

                    $lichSuTrungQua = [
                        'phone' => $phone,
                        'reward' => $idPhanThuong,
                        'status' => 1,
                        'created_at' => Carbon::now(),
                    ];
                    DB::beginTransaction();
                    lichSuTrungQua::create($lichSuTrungQua);
                    $phanThuong = phanThuong::find($idPhanThuong);
                    $phanThuong->used = $phanThuong->used + 1;
                    $phanThuong->save();
                    DB::commit();

                    return response()->json(['id' => $idPhanThuong]);
                }
            } else {
                return response()->json(['error' => 'SĐT đã được sử dụng trong hôm nay'], 500);
            }
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
