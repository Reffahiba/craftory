<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PenjualController;
use App\Http\Controllers\PembeliController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/logout_admin', [AuthController::class, 'logout_admin'])->name('logout-admin'); 
Route::post('/logout', [LoginController::class, 'logout'])->name('logout'); 

Route::middleware(['guest'])->group(function(){
    Route::get('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/login_proses', [LoginController::class, 'login_proses'])->name('login-proses');
    Route::get('/register', [LoginController::class, 'register'])->name('register');
    Route::post('/register_proses', [LoginController::class, 'register_proses'])->name('register-proses');
    Route::get('/register_toko', [LoginController::class, 'register_toko'])->name('register-toko'); 
    Route::post('/register_toko_proses', [LoginController::class, 'register_toko_proses'])->name('register-toko-proses'); 
    Route::get('/admin_login', [AuthController::class, 'admin_login'])->name('login-admin');
    Route::post('/admin_login_proses', [AuthController::class, 'admin_login_proses'])->name('login-admin-proses');
    Route::get('/admin_register', [AuthController::class, 'admin_register'])->name('register-admin');
    Route::post('/admin_register_proses', [AuthController::class, 'admin_register_proses'])->name('register-admin-proses');
});

Route::middleware(['auth', 'role:1'])->group(function(){
    Route::get('/admin/dashboard_admin', [AdminController::class, 'dashboard_admin'])->name('dashboard-admin');
    Route::get('/admin/profile_admin', [AdminController::class, 'profile_admin'])->name('profile-admin');
    Route::get('/admin/edit_profile/{id}', [AdminController::class, 'edit_profile_admin'])->name('edit-profile-admin');
    Route::post('/admin/update_profile/{id}', [AdminController::class, 'update_profile_admin'])->name('profile-update-admin');
    Route::get('/admin/data_kategori', [AdminController::class, 'data_kategori'])->name('data-kategori');
    Route::get('/admin/tambah_kategori', [AdminController::class, 'tambah_kategori'])->name('tambah-kategori');
    Route::post('/admin/tambah_kategori_proses', [AdminController::class, 'tambah_kategori_proses'])->name('kategori-store');
    Route::get('/admin/edit_kategori/{id}', [AdminController::class, 'edit_kategori'])->name('edit-kategori');
    Route::put('/admin/update_kategori/{id}', [AdminController::class, 'update_kategori'])->name('kategori-update');
    Route::delete('/admin/delete_kategori/{id}', [AdminController::class, 'delete_kategori'])->name('hapus-kategori');
    Route::get('/admin/data_toko', [AdminController::class, 'data_toko'])->name('data-toko');
    Route::get('/admin/edit_toko/{id}', [AdminController::class, 'edit_toko'])->name('edit-toko');
    Route::put('/admin/update_toko/{id}', [AdminController::class, 'update_toko'])->name('toko-update');
    Route::get('/admin/delete_toko/{id}', [AdminController::class, 'delete_toko'])->name('hapus-toko');
});

Route::middleware(['auth', 'role:2'])->group(function(){
    Route::get('/penjual/dashboard_penjual', [PenjualController::class, 'dashboard_penjual'])->name('dashboard-penjual');
    Route::get('/penjual/profile_penjual', [PenjualController::class, 'profile_penjual'])->name('profile-penjual');
    Route::get('/penjual/edit_profile/{id}', [PenjualController::class, 'edit_profile_penjual'])->name('edit-profile-penjual');
    Route::post('/penjual/update_profile/{id}', [PenjualController::class, 'update_profile_penjual'])->name('profile-update-penjual');
    Route::get('/penjual/data_produk', [PenjualController::class, 'data_produk'])->name('data-produk');
    Route::get('/penjual/tambah_produk', [PenjualController::class, 'tambah_produk'])->name('tambah-produk');
    Route::post('/penjual/tambah_produk_proses', [PenjualController::class, 'tambah_produk_proses'])->name('produk-store');
    Route::get('/penjual/edit_produk/{id}', [PenjualController::class, 'edit_produk'])->name('edit-produk');
    Route::put('/penjual/update_produk/{id}', [PenjualController::class, 'update_produk'])->name('produk-update');
    Route::delete('/penjual/delete_produk/{id}', [PenjualController::class, 'delete_produk'])->name('hapus-produk');
});

Route::middleware(['auth', 'role:3'])->group(function() {
    Route::get('/pembeli/dashboard_pembeli', [PembeliController::class, 'dashboard_pembeli'])->name('dashboard-pembeli');
    Route::get('/pembeli/profile_pembeli', [PembeliController::class, 'profile_pembeli'])->name('profile-pembeli');
    Route::get('/pembeli/edit_profile/{id}', [PembeliController::class, 'edit_profile_pembeli'])->name('edit-profile-pembeli');
    Route::post('/pembeli/update_profile/{id}', [PembeliController::class, 'update_profile_pembeli'])->name('profile-update-pembeli');
    Route::get('/pembeli/buat_pesanan', [PembeliController::class, 'buat_pesanan'])->name('buat-pesanan');
    Route::post('/pembeli/simpan_pesanan', [PembeliController::class, 'simpan_pesanan'])->name('simpan-pesanan');
    Route::get('/pembeli/keranjang', [PembeliController::class, 'keranjang'])->name('keranjang');
    Route::post('/pembeli/tambah_keranjang', [PembeliController::class, 'tambah_keranjang'])->name('tambah-keranjang');
    Route::post('/pembeli/update_keranjang/{id}/{action}', [PembeliController::class, 'update_keranjang'])->name('update-keranjang');
    Route::post('/pembeli/delete_keranjang/{id}', [PembeliController::class, 'hapus_keranjang'])->name('hapus-keranjang'); 
    Route::post('/pembeli/checkout', [PembeliController::class, 'checkOut'])->name('checkOut');
    Route::get('/pembeli/checkout_selesai/{id}', [PembeliController::class, 'checkOut_selesai'])->name('checkOut-selesai');
    Route::get('/pembeli/daftar_transaksi', [PembeliController::class, 'daftar_transaksi'])->name('daftar-transaksi'); 
});
