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

use App\Http\Controllers\LuckyWheelController;
use Illuminate\Support\Facades\Route;
Route::get('/gifts', [LuckyWheelController::class, 'gifts'])->name('luckywheel.gifts');
Route::get('/', [LuckyWheelController::class, 'index'])->name('luckywheel.index');
Route::get('/get-spin/{phone}', [LuckyWheelController::class, 'getSpin'])->name('luckywheel.getSpin');
Route::post('/get-spin/{phone}', [LuckyWheelController::class, 'getSpin']);
Route::get('/result/{phone}', [LuckyWheelController::class, 'result'])->name('luckywheel.result');
Route::post('/result/{phone}', [LuckyWheelController::class, 'result']);

Route::get('/logout', 'MyLoginController@logOut')->name("logOut");
Route::get('/login', 'MyLoginController@getLogin')->name("login");
Route::post('/login', 'MyLoginController@postLogin')->name("postLogin");
Route::get('/register', 'MyLoginController@getRegister')->name("getRegister");
Route::post('/register', 'MyLoginController@postRegister')->name("postRegister");
Route::post('/them-moi-phan-thuong', 'HomeController@themMoiPhanThuong');
Route::post('/xoa-phan-thuong', 'HomeController@xoaPhanThuong');
Route::post('/admin/danh-sach-qua', 'HomeController@danhSachQua')->name("admin.danhSachQua");
Route::group(['prefix' => 'admin', 'middleware' => ['checkLogin']], function(){
    Route::get('/', 'HomeController@getHome')->name("admin.getHome");
    Route::get('/quan-ly', 'HomeController@quanLyPhanThuong')->name("admin.quanLyPhanThuong");
    Route::get('/lich-su', 'HomeController@lichSuQuay')->name("admin.lichSu");
    Route::post('/danh-sach-trung-qua', 'HomeController@danhSachTrungQua')->name("admin.danhSachTrungQua");
    Route::post('/chinh-sua-phan-thuong', 'HomeController@suaPhanThuong');
});


Auth::routes();
