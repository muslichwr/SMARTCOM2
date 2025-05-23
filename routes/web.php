<?php

use App\Http\Controllers\Admin\PJBLKelompokController;
use App\Http\Controllers\Admin\PJBLSintaksController;
use App\Http\Controllers\Admin\RiwayatController;
use App\Models\PrePostTest;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BantuanController;
use App\Http\Controllers\Admin\BabController;
use App\Http\Controllers\Admin\TestController;
use App\Http\Controllers\Admin\MateriController;
use App\Http\Controllers\Admin\LatihanController;
use App\Http\Controllers\Admin\PrePostController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PrePostTestController;
use App\Http\Controllers\Admin\SoalJawabanController;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\User\MateriController as UserMateriController;

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/bantuan', [BantuanController::class, 'index'])->name('bantuan.index');

});

Route::get('/', [FrontendController::class, 'index']);
Route::get('/tentang', [FrontendController::class, 'about']);

Route::prefix('user')->middleware(['auth'])->group(function () {
    Route::controller(UserMateriController::class)->group(function () {
        Route::get('/materi', 'index')->name('user.materi.index');

        //Todo: Pelajari Materi
        Route::get('/materi/{slug}', 'buka');
        Route::get('/materi/{slug}/pelajari', 'pelajari');
        Route::post('/materi/{slug}/selesaiMateri', 'selesaikanMateri');

        Route::get('/materi/{slug}/sintaks', 'tampilkanSintaks')->name('user.materi.sintaks');
        Route::get('/materi/{slug}/sintaks/tahap1', 'tahap1')->name('user.materi.tahap1');
        Route::post('/materi/{slug}/sintaks/tahap1', 'simpanTahap1');
        Route::get('/materi/{slug}/sintaks/tahap2', 'tahap2')->name('user.materi.tahap2');
        Route::post('/materi/{slug}/sintaks/tahap2', 'simpanTahap2');
        Route::get('/materi/{slug}/sintaks/tahap3', 'tahap3')->name('user.materi.tahap3');
        Route::post('/materi/{slug}/sintaks/tahap3', 'simpanTahap3');
        Route::post('/materi/{slug}/sintaks/tahap3/auto-validasi', 'autoValidasiTahap3')->name('user.materi.autoValidasiTahap3');
        Route::get('/materi/{slug}/sintaks/tahap4', 'tahap4')->name('user.materi.tahap4');
        Route::post('/materi/{slug}/sintaks/tahap4', 'simpanTahap4');
        Route::post('/materi/{slug}/sintaks/tahap4/tambah-tugas', 'tambahTugasTahap4')->name('user.materi.tambahTugasTahap4');
        
        Route::get('/materi/{slug}/sintaks/tahap5', 'tahap5')->name('user.materi.tahap5');
        Route::post('/materi/{slug}/sintaks/tahap5', 'simpanTahap5');
        Route::get('/materi/{slug}/sintaks/tahap6', 'tahap6')->name('user.materi.tahap6');
        Route::post('/materi/{slug}/sintaks/tahap6', 'simpanTahap6');
        Route::get('/materi/{slug}/sintaks/tahap7', 'tahap7')->name('user.materi.tahap7');
        Route::post('/materi/{slug}/sintaks/tahap7', 'mintaPenilaian');
        Route::get('/materi/{slug}/sintaks/tahap8', 'tahap8')->name('user.materi.tahap8');
        Route::post('/materi/{slug}/sintaks/tahap8', 'simpanTahap8')->name('user.materi.simpan-tahap8');
        Route::post('/materi/{slug}/sintaks/tahap8/refleksi', 'simpanRefleksiIndividu')->name('user.materi.simpan-refleksi-individu');
        //Todo: Latihan
        Route::get('/latihan/{materiSlug}', 'bukaLatihan');
        Route::get('/latihan/{slug}/kerjakan', 'kerjakan');
        Route::post('/latihan/jawab', 'jawab');

        //Todo: Pre Test
        Route::get('/pretest', 'bukaPreTest')->name('pretest');
        Route::post('/pretest/jawab', 'jawabPreTest');

        //Todo: Post Test
        Route::get('/postest', 'bukaPostTest');
        Route::post('/postest/jawab', 'jawabPostTest');
    });
});

Route::prefix('admin')->middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('dashboard', [DashboardController::class,'index'])->name('admin.dashboard');

    // Sintaks Route
    Route::controller(PJBLSintaksController::class)->group(function () {
        // Halaman utama (daftar materi)
        Route::get('pjbl/sintaks', 'index')->name('admin.pjbl.sintaks.index');

        // Toggle PJBL Sintaks aktif/nonaktif
        Route::post('pjbl/sintaks/{id}/toggle', 'togglePJBLSintaks')->name('admin.pjbl.sintaks.toggle');

        // Daftar kelompok di materi tertentu
        Route::get('pjbl/sintaks/{materi}/kelompok', 'listKelompok')->name('admin.pjbl.sintaks.kelompok');

        // Detail sintaks kelompok
        Route::get('pjbl/sintaks/{materi}/{kelompok}/detail', 'detailSintaks')->name('admin.pjbl.sintaks.detail');

        // Validasi sintaks di setiap tahap
        Route::post('pjbl/sintaks/{materi}/{kelompok}/validasi', 'validasiTahap')->name('admin.pjbl.sintaks.validasi');

        // Beri nilai dan feedback (khusus tahap 7)
        Route::post('pjbl/sintaks/{materi}/{kelompok}/nilai', 'beriNilai')->name('admin.pjbl.sintaks.beri-nilai');

        // Update data di setiap tahap
        Route::post('pjbl/sintaks/{materi}/{kelompok}/update', 'updateTahap')->name('admin.pjbl.sintaks.update');
    });

    // PJBL Kelompok Route
    Route::controller(PJBLKelompokController::class)->group(function () {
        Route::get('pjbl/kelompok', 'index')->name('kelompok.index');
        Route::get('pjbl/kelompok/create', 'create')->name('kelompok.create');
        Route::post('pjbl/kelompok', 'store')->name('kelompok.store');
        Route::get('pjbl/kelompok/{slug}/edit', 'edit')->name('kelompok.edit');
        Route::put('pjbl/kelompok/{slug}', 'update')->name('kelompok.update');
        Route::get('pjbl/kelompok/{slug}', 'show')->name('kelompok.show');
        Route::post('pjbl/kelompok/{slug}/addAnggota', 'addAnggota')->name('kelompok.addAnggota');
        Route::post('pjbl/kelompok/{slug}/setKetua', 'setKetua')->name('kelompok.setKetua');
        Route::delete('pjbl/kelompok/{slug}', 'destroy')->name('kelompok.destroy'); // Update rute destroy dengan slug
    });

    //? Akun Route
    Route::controller(UserController::class)->group(function () {
        Route::get('/users', 'index');
        Route::get('/users/create', 'create');
        Route::post('/users', 'store');
        Route::get('/users/{user}/edit','edit');
        Route::put('/users/{user}', 'update');
        Route::delete('/users/{user}', 'destroy');

        Route::get('/users/bulk-create', 'showBulkCreateForm')->name('users.bulk-create');
        Route::post('/users/bulk-create', 'bulkCreate');
        Route::get('/admin/users/download-template', 'downloadTemplate')->name('admin.users.downloadTemplate');
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

});

// Route untuk guru
Route::prefix('guru')->middleware(['auth', 'isGuru'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index']);

    Route::controller(MateriController::class)->group(function () {
        Route::get('/materi', 'index');
        Route::get('/materi/create', 'create');
        Route::post('/materi', 'store');
        Route::get('/materi/{materi}/edit','edit');
        Route::put('/materi/{materi}', 'update');
        Route::delete('/materi/{materi}', 'destroy');
    });

    Route::controller(BabController::class)->group(function () {
        Route::get('/bab', 'index');
        Route::get('/bab/create', 'create');
        Route::post('/bab', 'store');
        Route::get('/bab/{bab}/edit','edit');
        Route::put('/bab/{bab}', 'update');
        Route::delete('/bab/{bab}', 'destroy');
    });

    Route::controller(LatihanController::class)->group(function () {
        Route::get('/latihan', 'index');
        Route::get('/latihan/create', 'create');
        Route::post('/latihan', 'store');
        Route::get('/latihan/{latihan}/edit', 'edit');
        Route::put('/latihan/{latihan}', 'update');
        Route::delete('/latihan/{latihan}', 'destroy');
    });

    Route::controller(RiwayatController::class)->group(function () {
        Route::get('/riwayat', 'index')->name('admin.riwayat.index');
        Route::get('/riwayat/{user}/lihat', 'cekProgress');
        Route::get('/riwayat/{user}/lihat/{prepost}', 'cekDetail');
    });
});

require __DIR__.'/auth.php';
