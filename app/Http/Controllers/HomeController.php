<?php

namespace App\Http\Controllers;

use App\lichSuTrungQua;
use App\phanThuong;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function getHome()
    {
        return view('admin.home');
    }

    public function quanLyPhanThuong()
    {
        return view('admin.quan-ly-phan-thuong');
    }

    public function lichSuQuay()
    {
        return view('admin.lich-su-quay');
    }

    public function danhSachQua(Request $request)
    {
        $req = $request->all();
        $data = phanThuong::orderBy('created_at', 'DESC')->skip($req['start'])->take($req['limit'])->get();
        $res = [
            'rc' => 0,
            'rd' => 'Lấy dữ liệu thành công',
            'history' => $data,
            'total' => phanThuong::count()
        ];
        return json_encode($res);
    }

    public function suaPhanThuong(Request $request)
    {
        $req = $request->all();
        $qua = phanThuong::where('id', $req['id'])->first();

        $qua->name = $req['name'];
        $qua->value = $req['value'];
        $qua->ratio = $req['ratio'];
        $qua->amount = $req['amount'];
        $qua->used = $req['used'];
        $qua->save();
        $res = [
            'rc' => '0',
            'rd' => 'Bạn đã sửa thành công',
        ];
        return json_encode($res);

    }

    public function xoaPhanThuong(Request $request)
    {
        $data = $request->all();
        $checkTrung = lichSuTrungQua::where('reward', $data['id'])->first();
        if ($checkTrung != null) {
            $res = [
                'rc' => '-1',
                'rd' => 'Đã có người quay trúng phần quà này. Bạn không thể xoá.',
            ];
            return json_encode($res);

        } else {
            $qua = phanThuong::where('id', $data['id'])->first();
            $qua->delete();
            $res = [
                'rc' => '0',
                'rd' => 'Xoá phần quà thành công',
            ];
            return json_encode($res);

        }
    }

    public function themMoiPhanThuong(Request $request)
    {

        $req = $request->all();
        $hinhAnh = $request->file('image');
        $filePathPhanThuong = '/phanThuong/' . uniqid() . '.' . $hinhAnh->extension();
        $hinhAnh->move(public_path('phanThuong'), $filePathPhanThuong);
        $dataCreat = phanThuong::create([
            'name' => $req['name'],
            'value' => $req['value'],
            'ratio' => $req['ratio'],
            'amount' => $req['amount'],
            'used' => 0,
            'image' => $filePathPhanThuong,
        ]);
        $res = [
            'rc' => 0,
            'rd' => 'Thêm phần thưởng thành công',
            'data' => $dataCreat
        ];
        return json_encode($res);
    }

    public function danhSachTrungQua(Request $request)
    {
        $req = $request->all();
        $bill = lichSuTrungQua::where('phone', 'like', '%' . $req['phone'] . '%');
        $data = DB::table('lich-su-trung-qua')
            ->leftJoin('phan-thuong', 'lich-su-trung-qua.reward', '=', 'phan-thuong.id')
            ->orderBy('lich-su-trung-qua.created_at', 'DESC')
            ->skip($req['start'])->take($req['limit'])->get();
//        $data = $bill->orderBy('created_at', 'DESC')->skip($req['start'])->take($req['limit'])->get();
        $res = [
            'rc' => 0,
            'rd' => 'Lấy dữ liệu thành công',
            'history' => $data,
            'total' => lichSuTrungQua::where('phone', 'like', '%' . $req['phone'] . '%')->count()
        ];
        return json_encode($res);
    }
}
