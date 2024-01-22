<?php

use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\UserController;
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
    if (auth()->check()) {
        return redirect()->route('home');
    }
    return redirect()->route('login');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('home');
    // anggota
    Route::post('/anggota/import', [AnggotaController::class, 'import'])->name('anggota.import');
    Route::get('/anggota', [AnggotaController::class, 'index'])->name('anggota');    
    Route::post('/anggota/store', [AnggotaController::class, 'store'])->name('anggota.store');

    // akhir route anggota

    Route::middleware(['hak.access:users'])->group(function () {
        Route::get('/master/users', [UserController::class, 'index'])->name('users');
        Route::middleware(['hak.access:users_manage'])->group(function () {
            Route::post('/master/users', [UserController::class, 'upsert'])->name('users.upsert');
            Route::get('/master/users/{id}', [UserController::class, 'show'])->name('users.show');
            Route::get('/master/users/{id}/delete', [UserController::class, 'delete'])->name('users.delete');
        });
    });

    Route::get('/master/jadwal', [JadwalController::class, 'index'])->name('jadwal');
    Route::post('/master/jadwal/store', [JadwalController::class, 'store'])->name('jadwal_eskuls.store');
});
