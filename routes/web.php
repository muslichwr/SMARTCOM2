<?php

use App\Http\Controllers\Admin\PjBLController;
use App\Http\Controllers\Admin\RiwayatController;
use App\Models\PrePostTest;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\BabController;
use App\Http\Controllers\Admin\TestController;
use App\Http\Controllers\Admin\MateriController;
use App\Http\Controllers\Admin\LatihanController;
use App\Http\Controllers\Admin\PrePostController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PrePostTestController;
use App\Http\Controllers\Admin\SoalJawabanController;
use App\Http\Controllers\Admin\TopicController;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\User\MateriController as UserMateriController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/', [FrontendController::class, 'index'])->middleware(['auth']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/', [FrontendController::class, 'index']);
Route::get('/tentang', [FrontendController::class, 'about']);

Route::prefix('user')->middleware(['auth'])->group(function () {
    Route::controller(UserMateriController::class)->group(function () {
        Route::get('/materi', 'index');

        //Todo: Pelajari Materi
        Route::get('/materi/{slug}', 'buka');
        Route::get('/materi/{slug}/pelajari', 'pelajari');
        Route::post('/materi/{slug}/selesaiMateri', 'selesaikanMateri');

        //Todo: Latihan
        Route::get('/latihan/{materiSlug}', 'bukaLatihan');
        Route::get('/latihan/{slug}/kerjakan', 'kerjakan');
        Route::post('/latihan/jawab', 'jawab');

        //Todo: Pre Test
        Route::get('/pretest', 'bukaPreTest');
        Route::post('/pretest/jawab', 'jawabPreTest');

        //Todo: Post Test
        Route::get('/postest', 'bukaPostTest');
        Route::post('/postest/jawab', 'jawabPostTest');
    });
});

Route::prefix('admin')->middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('dashboard', [DashboardController::class,'index']);

    //? Topics Route
    Route::controller(TopicController::class)->group(function () {
        Route::get('/topic', 'index');
        Route::get('/topic/create', 'create');
        Route::post('/topic', 'store');
        Route::get('/topic/{topic}/edit','edit');
        Route::put('/topic/{topic}', 'update');
        Route::delete('/topic/{topic}', 'destroy');
    });

    //? Materi Route
    Route::controller(MateriController::class)->group(function () {
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

    //? Pre & Post
    Route::controller(PrePostController::class)->group(function () {
        Route::get('/PrePost', 'index');
        Route::get('/PrePost/create', 'create');
        Route::post('/PrePost', 'store');
        Route::get('/PrePost/{PrePost}/edit', 'edit');
        Route::put('/PrePost/{PrePost}', 'update');
        Route::delete('/PrePost/{PrePost}', 'destroy');
    });

    //? Pre & Post Test
    Route::controller(PrePostTestController::class)->group(function () {
        Route::get('/PrePostTest', 'index');
        Route::get('/get-soaljawabanprepost', 'getQuestion')->name('get-soaljawabanprepost'); //* Add Soal&Jawaban
        Route::post('/add-soaljawabanprepost', 'addQuestion')->name('add-soaljawabanprepost');
        Route::get('/get-preposttest-soaljawaban', 'getPrePostTestQuestion')->name('get-preposttest-soaljawaban');
        Route::get('/delete-preposttest-soaljawaban', 'deletePrePostTestQuestion')->name('delete-preposttest-soaljawaban');
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

    //? Riwayat
    Route::controller(RiwayatController::class)->group(function () {
        Route::get('/riwayat', 'index');

        //? Cek Progress
        Route::get('/riwayat/{user}/lihat', 'cekProgress');

        //? Cek detail pre post
        Route::get('/riwayat/{user}/lihat/{prepost}', 'cekDetail');
    });

    //? Proyek
    Route::controller(PjBLController::class)->group(function () {
        Route::get('/proyek/kelompok', 'index');
        Route::post('/proyek/kelompok', 'store');
        Route::put('/proyek/kelompok/{kelompok}', 'update');
        Route::delete('/proyek/kelompok/{kelompok}', 'destroy');

        //Todo: Anggota Kelompok
        Route::get('/proyek/anggotaKelompok', 'indexAnggota');

        Route::get('/proyek/anggotaKelompok/get-user', 'getUser')->name('get-user'); //* Add Soal&Jawaban
        Route::post('/proyek/anggotaKelompok/add-user', 'addUser')->name('add-user');
        Route::get('/proyek/anggotaKelompok/get-anggota', 'getAnggotaKelompok')->name('get-anggota');
        Route::get('/proyek/anggotaKelompok/delete-anggota', 'deleteAnggotaKelompok')->name('delete-anggota');
    });

});

require __DIR__.'/auth.php';
