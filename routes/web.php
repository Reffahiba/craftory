<?php

use App\Http\Controllers\AuthController;
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

Route::get('/sign-in', [AuthController::class, 'signIn']);

Route::get('/register', [AuthController::class, 'create'])->name('user.create');

Route::post('/sign-in', [AuthController::class, 'store'])->name('user.store');  
