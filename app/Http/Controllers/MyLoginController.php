<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Session;
use App\User;
use Hash;


class MyLoginController extends Controller
{
    public function xuanNhan(){

        return view('xuannhan');
    }

    public function getLogin()
    {
        return view('auth.login');
    }

    public function getRegister()
    {
        return view('auth.register');
    }

    public function postRegister(Request $request)
    {
        $data = $request->all();
        $user = User::where('name', '=', $data['userName'])->first();
        $checkMail = User::where('email', '=', $data['email'])->first();
        if($checkMail!=null){

            $res = [
                'rc' => -1,
                'rd' => 'Email đã tổn tại trên hệ thống.',
            ];
            return json_encode($res);
        }
        if ($user != null) {
            $res = [
                'rc' => -1,
                'rd' => 'Tài khoản đã có trên hệ thống. Vui lòng thử lại với tên đăng nhập khác.',
            ];
        } else {
            User::create([
                'email' => $data['email'],
                'name' => $data['userName'],
                'password_transfer' => $data['password'],
                'password' => Hash::make($data['password'])
            ]);
            $res = [
                'rc' => 0,
                'rd' => 'Đăng ký thành công. Bạn có thể đăng nhập với tài khoản đã tạo.',
            ];
        }

        return json_encode($res);
    }

    public function postLogin(Request $request)
    {
        $credentials = $request->only('name', 'password');
        if (Auth::attempt($credentials)) {
            Log::info(Auth::user()->status);
            $res = [
                'rc' => 0,
                'rd' => 'Đăng nhập thành công',
                'data'=>Auth::user()
            ];
        } else {
            $res = [
                'rc' => -1,
                'rd' => 'Tài khoản hoặc mật khẩu không chính xác.',
            ];
        }
        return json_encode($res);
    }


    public function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);
    }

    public function logOut()
    {
        Auth::logout();
        return redirect('/');
    }
}
