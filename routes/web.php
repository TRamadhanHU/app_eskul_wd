<?php

use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\EskulController;
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

    Route::middleware(['hak.access:users'])->group(function () {
        Route::get('/master/users', [UserController::class, 'index'])->name('users');
        Route::middleware(['hak.access:users_manage'])->group(function () {
            Route::post('/master/users', [UserController::class, 'upsert'])->name('users.upsert');
            Route::get('/master/users/{id}', [UserController::class, 'show'])->name('users.show');
            Route::delete('/master/users/{id}/delete', [UserController::class, 'delete'])->name('users.delete');
        });
    });

    Route::middleware(['hak.access:eskul'])->group(function () {
        Route::get('master/eskul', [EskulController::class, 'index'])->name('eskul');
        Route::middleware(['hak.access:eskul_manage'])->group(function () {
            Route::post('/master/eskul', [EskulController::class, 'upsert'])->name('eskul.upsert');
            Route::get('/master/eskul/{id}', [EskulController::class, 'show'])->name('eskul.show');
            Route::delete('/master/eskul/{id}/delete', [EskulController::class, 'delete'])->name('eskul.delete');

        });
    });

    Route::middleware(['hak.access:jadwal'])->group(function () {
        Route::get('/master/jadwal', [JadwalController::class, 'index'])->name('jadwal');
        Route::middleware(['hak.access:jadwal_manage'])->group(function () {
            Route::post('/master/jadwal/store', [JadwalController::class, 'store'])->name('jadwal_eskuls.store');
            Route::get('/master/jadwal/{id}', [JadwalController::class, 'show'])->name('jadwal_eskuls.show');
            Route::post('/master/jadwal/update', [JadwalController::class, 'update'])->name('jadwal_eskuls.update');
            Route::delete('/master/jadwal/{id}', [JadwalController::class, 'delete'])->name('jadwal_eskuls.delete');
        });
    });

    Route::middleware(['hak.access:anggota'])->group(function () {
        Route::get('/anggota', [AnggotaController::class, 'index'])->name('anggota');    
        Route::middleware(['hak.access:anggota_manage'])->group(function () {
            Route::post('/anggota/import', [AnggotaController::class, 'import'])->name('anggota.import');
            Route::get('/anggota/template-import', [AnggotaController::class, 'templateImport'])->name('anggota.template-import');
            Route::get('/anggota/{id}', [AnggotaController::class, 'show'])->name('anggota.show');
            Route::post('/anggota/upsert', [AnggotaController::class, 'upsert'])->name('anggota.upsert');
            Route::delete('/anggota/{id}/delete', [AnggotaController::class, 'delete'])->name('anggota.delete');
        });
    });

    // test view
    Route::get('/test', function () {
        return view('anggotaUi.index');
    });

});
