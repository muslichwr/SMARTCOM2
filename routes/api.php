<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\BabController;
use App\Http\Controllers\Admin\TestController;
use App\Http\Controllers\User\MateriController;
use App\Http\Controllers\Admin\LatihanController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MateriController as AdminMateriController;
use App\Http\Controllers\Admin\SoalJawabanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Frontend\FrontendController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(TestController::class)->group(function () {
    Route::get('/test', 'index');
    Route::get('/get-soaljawaban', 'getQuestion')->name('getQuestion'); //* Add Soal&Jawaban
});

Route::controller(MateriController::class)->group(function () {
    Route::post('/latihan/jawab', 'jawab');
});

Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/', [FrontendController::class, 'index']);
Route::get('/tentang', [FrontendController::class, 'about']);

Route::prefix('user')->middleware(['auth'])->group(function () {
    Route::controller(MateriController::class)->group(function () {
        Route::get('/materi', 'index');

        //Todo: Pelajari Materi
        Route::get('/materi/{materi}', 'buka');
        Route::get('/materi/{bab}/pelajari', 'pelajari');
        Route::post('/materi/{bab}/selesaiMateri', 'selesaikanMateri');

        //Todo: Latihan
        Route::get('/latihan/{materi}', 'bukaLatihan');
        Route::get('/latihan/{latihan}/kerjakan', 'kerjakan');
        Route::post('/latihan/jawab', 'jawab');
    });
});

Route::prefix('admin')->middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('dashboard', [DashboardController::class,'index']);

    //? Materi Route
    Route::controller(AdminMateriController::class)->group(function () {
        Route::get('/materi', 'index');
        Route::get('/materi/create', 'create');
        Route::post('/materi', 'store');
        Route::get('/materi/{materi}/edit','edit');
        Route::put('/materi/{materi}', 'update');
        Route::delete('/materi/{materi}', 'destroy');
    });

    //? Bab Route
    Route::controller(BabController::class)->group(function () {
        Route::get('/bab', 'index');
        Route::get('/bab/create', 'create');
        Route::post('/bab', 'store');
        Route::get('/bab/{bab}/edit','edit');
        Route::put('/bab/{bab}', 'update');
        Route::delete('/bab/{bab}', 'destroy');
    });

    //? Latihan
    Route::controller(LatihanController::class)->group(function () {
        Route::get('/latihan', 'index');
        Route::get('/latihan/create', 'create');
        Route::post('/latihan', 'store');
        Route::get('/latihan/{latihan}/edit', 'edit');
        Route::put('/latihan/{latihan}', 'update');
        Route::delete('/latihan/{latihan}', 'destroy');
    });

    //? QnA
    Route::controller(SoalJawabanController::class)->group(function () {
        Route::get('/soal-jawaban', 'index');
        Route::get('/soal-jawaban/create', 'create');
        Route::post('/soal-jawaban', 'store');
        Route::get('/soal-jawaban/{soal}/edit', 'edit');
        Route::put('/soal-jawaban/{soal}', 'update');
    });

    //? Test
    Route::controller(TestController::class)->group(function () {
        Route::get('/test', 'index');
        Route::get('/get-soaljawaban', 'getQuestion')->name('get-soaljawaban'); //* Add Soal&Jawaban
        Route::post('/add-soaljawaban', 'addQuestion')->name('add-soaljawaban');
        Route::get('/get-test-soaljawaban', 'getTestQuestion')->name('get-test-soaljawaban');
        Route::get('/delete-test-soaljawaban', 'deleteTestQuestion')->name('delete-test-soaljawaban');
    });

});
