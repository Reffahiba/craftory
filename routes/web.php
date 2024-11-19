<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
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

Route::get('/admin_login', [AuthController::class, 'admin_login'])->name('login-admin');
Route::post('/admin_login_proses', [AuthController::class, 'admin_login_proses'])->name('login-admin-proses');
Route::get('/admin_register', [AuthController::class, 'admin_register'])->name('register-admin');
Route::post('/admin_register_proses', [AuthController::class, 'admin_register_proses'])->name('register-admin-proses');
Route::post('/logout_admin', [AuthController::class, 'logout_admin'])->name('logout-admin'); 

Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/login_proses', [LoginController::class, 'login_proses'])->name('login-proses');
Route::get('/register', [LoginController::class, 'register'])->name('register');
Route::post('/register_proses', [LoginController::class, 'register_proses'])->name('register-proses');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');  

Route::middleware('auth')->group(function(){
    Route::get('/dashboard_penjual', [UserController::class, 'dashboard_penjual'])->name('dashboard-penjual')->middleware('auth');
    Route::get('/dashboard_admin', [UserController::class, 'dashboard_admin'])->name('dashboard-admin');
    Route::get('/tambah_kategori', [UserController::class, 'tambah_kategori'])->name('tambah-kategori');
    Route::get('/tambah_produk', [UserController::class, 'tambah_produk'])->name('tambah-produk');
});
Route::post('/tambah_kategori_proses', [UserController::class, 'tambah_kategori_proses'])->name('kategori-store');
Route::get('/edit_kategori/{id}', [UserController::class, 'edit_kategori'])->name('edit-kategori');
Route::put('/update_kategori/{id}', [UserController::class, 'update_kategori'])->name('kategori-update');
Route::delete('/delete_kategori/{id}', [UserController::class, 'delete_kategori'])->name('hapus-kategori');

Route::post('/tambah_produk_proses', [UserController::class, 'tambah_produk_proses'])->name('produk-store');
Route::get('/edit_produk/{id}', [UserController::class, 'edit_produk'])->name('edit-produk');
Route::put('/update_produk/{id}', [UserController::class, 'update_produk'])->name('produk-update');
Route::delete('/delete_produk/{id}', [UserController::class, 'delete_produk'])->name('hapus-produk');