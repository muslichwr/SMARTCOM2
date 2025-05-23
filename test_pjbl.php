<?php

// Autoload composer
require __DIR__.'/vendor/autoload.php';

// Load Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🧪 Mulai pengetesan PJBL dengan status validasi dan feedback\n";
// 0. Buat User
$user = App\Models\User::factory()->create();

// 1. Buat Kelompok & Materi
$kelompok = App\Models\Kelompok::factory()->create([
    'kelompok' => 'Kelompok Inovasi Lingkungan'
]);
echo "✅ Kelompok berhasil dibuat: " . $kelompok->kelompok . "\n";

$materi = App\Models\Materi::factory()->create([
    'judul' => 'Pengolahan Limbah Plastik',
    'pjbl_sintaks_active' => true
]);
echo "✅ Materi berhasil dibuat: " . $materi->judul . "\n";

// 2. Buat SintaksBaru
$sintaks = App\Models\SintaksBaru::create([
    'kelompok_id' => $kelompok->id,
    'materi_id' => $materi->id,
    'status_validasi' => 'pending',
    'feedback_guru' => null,
    'total_nilai' => null
]);
echo "✅ SintaksBaru berhasil dibuat dengan ID: " . $sintaks->id . "\n";

// 3. Tes Relasi ke Tahap 1: Orientasi Masalah
$tahap1 = App\Models\SintaksTahapSatu::create([
    'sintaks_id' => $sintaks->id,
    'orientasi_masalah' => 'Permasalahan sampah plastik yang semakin menumpuk di lingkungan sekitar sekolah dan rumah siswa.',
    'rumusan_masalah' => 'Bagaimana cara mengurangi volume sampah plastik dan memanfaatkannya menjadi produk bernilai ekonomi?',
    'file_analisis' => 'analisis_sampah_plastik.pdf',
    'status_validasi' => 'valid',
    'feedback_guru' => 'Orientasi masalah sudah bagus dan sesuai dengan kondisi lingkungan sekolah. Lanjutkan ke tahap berikutnya.'
]);

// Cek relasi
$sintaks->load('tahapSatu');
echo "✅ Relasi SintaksBaru->tahapSatu: " . ($sintaks->tahapSatu ? "Berhasil" : "Gagal") . "\n";
echo "   - Orientasi masalah: {$sintaks->tahapSatu->orientasi_masalah}\n";
echo "   - Status validasi: {$sintaks->tahapSatu->status_validasi}\n";
echo "   - Feedback guru: {$sintaks->tahapSatu->feedback_guru}\n";

// 4. Tes Relasi ke Tahap 2: Rancangan Proyek
$tahap2 = App\Models\SintaksTahapDua::create([
    'sintaks_id' => $sintaks->id,
    'file_rancangan' => 'rancangan_daur_ulang.pdf',
    'deskripsi_rancangan' => 'Rencana pembuatan ecobrick dari botol plastik bekas dan pengembangan kerajinan daur ulang plastik menjadi tas dan dompet.',
    'status_validasi' => 'valid',
    'feedback_guru' => 'Rancangan proyek sudah detail dan sistematis. Pertimbangkan juga aspek pemasaran produk yang akan dihasilkan.'
]);

// Cek relasi
$sintaks->load('tahapDua');
echo "✅ Relasi SintaksBaru->tahapDua: " . ($sintaks->tahapDua ? "Berhasil" : "Gagal") . "\n";
echo "   - Deskripsi: {$sintaks->tahapDua->deskripsi_rancangan}\n";
echo "   - Status validasi: {$sintaks->tahapDua->status_validasi}\n";
echo "   - Feedback guru: {$sintaks->tahapDua->feedback_guru}\n";

// 5. Tes Relasi ke Tahap 3: Jadwal Proyek
$tahap3 = App\Models\SintaksTahapTiga::create([
    'sintaks_id' => $sintaks->id,
    'file_jadwal' => 'jadwal_proyek_daur_ulang.xlsx',
    'jadwal_mulai' => '2025-05-01',
    'jadwal_selesai' => '2025-06-30',
    'tugas_anggota' => json_encode([
        'Pengumpulan sampah plastik dari lingkungan sekolah',
        'Pemilahan jenis plastik berdasarkan kategori',
        'Pembuatan ecobrick dari botol plastik',
        'Pembuatan kerajinan tas dari plastik kemasan',
        'Dokumentasi dan pelaporan proses kerja'
    ]),
    'status_validasi' => 'valid',
    'feedback_guru' => 'Pembagian tugas sudah merata dan timeline proyek realistis. Pastikan semua anggota memahami tugasnya.'
]);

// Cek relasi
$sintaks->load('tahapTiga');
echo "✅ Relasi SintaksBaru->tahapTiga: " . ($sintaks->tahapTiga ? "Berhasil" : "Gagal") . "\n";
echo "   - Jadwal mulai: {$sintaks->tahapTiga->jadwal_mulai}, Jadwal selesai: {$sintaks->tahapTiga->jadwal_selesai}\n";
echo "   - Status validasi: {$sintaks->tahapTiga->status_validasi}\n";
echo "   - Feedback guru: {$sintaks->tahapTiga->feedback_guru}\n";

// 6. Tes Relasi ke Tahap 4: Pelaksanaan Proyek
$tahap4 = App\Models\SintaksTahapEmpat::create([
    'sintaks_id' => $sintaks->id,
    'status_validasi' => 'valid',
    'feedback_guru' => 'Pelaksanaan proyek berjalan dengan lancar dan sesuai jadwal. Dokumentasi proses kerja sangat baik.'
]);

// Cek relasi
$sintaks->load('tahapEmpat');
echo "✅ Relasi SintaksBaru->tahapEmpat: " . ($sintaks->tahapEmpat ? "Berhasil" : "Gagal") . "\n";
echo "   - Status validasi: {$sintaks->tahapEmpat->status_validasi}\n";
echo "   - Feedback guru: {$sintaks->tahapEmpat->feedback_guru}\n";

// Pastikan ada user
$users = [];
for($i = 1; $i <= 5; $i++) {
    $randomStr = substr(md5(rand()), 0, 6); // Generate random string
    $user = App\Models\User::factory()->create([
        'name' => "Anggota Kelompok {$i}",
        'email' => "anggota{$i}_{$randomStr}@example.com"
    ]);
    $users[] = $user;
    
    // Buat anggota kelompok
    App\Models\Anggota::create([
        'kelompok_id' => $kelompok->id,
        'user_id' => $user->id
    ]);
}
echo "✅ 5 User dan anggota kelompok berhasil dibuat\n";

// Tambahkan beberapa tugas pelaksanaan:
$tugasLabels = [
    'Mengumpulkan botol plastik dari lingkungan sekolah',
    'Membersihkan dan mengeringkan botol plastik',
    'Membuat ecobrick dari botol plastik',
    'Mengumpulkan plastik kemasan untuk bahan tas',
    'Membuat prototype tas dari plastik kemasan'
];

$tugasStatus = ['belum_mulai', 'proses', 'selesai'];

foreach($users as $index => $user) {
    $randomStatus = $tugasStatus[array_rand($tugasStatus)];
    $task = App\Models\SintaksTahapEmpatTugas::create([
        'sintaks_pelaksanaan_id' => $tahap4->id,
        'user_id' => $user->id,
        'judul_task' => "Tugas ".($index+1),
        'deskripsi_task' => $tugasLabels[$index],
        'status' => $randomStatus,
        'deadline' => date('Y-m-d', strtotime("+{$index} weeks")),
        'catatan' => "Catatan untuk tugas ".($index+1),
        'file_tugas' => "file_tugas_".($index+1).".pdf"
    ]);
}

// Cek relasi
$tahap4->load('tasks');
echo "✅ Relasi SintaksTahapEmpat->tasks: " . ($tahap4->tasks->count() ? "Berhasil ({$tahap4->tasks->count()} tugas)" : "Gagal") . "\n";
echo "   - Tugas pertama: {$tahap4->tasks->first()->deskripsi_task}\n";

// 7. Tes Relasi ke Tahap 5: Hasil Karya Proyek
$tahap5 = App\Models\SintaksTahapLima::create([
    'sintaks_id' => $sintaks->id,
    'file_karya' => 'hasil_karya_daur_ulang.zip',
    'deskripsi_karya' => 'Koleksi 20 ecobrick dari botol plastik dan 5 tas dari plastik kemasan yang telah berhasil dibuat oleh kelompok.',
    'progress_karya' => json_encode([
        [
            'tugas' => 'Mengumpulkan botol plastik',
            'user_id' => $users[0]->id,
            'nama_anggota' => $users[0]->name,
            'status' => 'selesai',
            'file_tugas' => 'dokumentasi_pengumpulan.pdf'
        ],
        [
            'tugas' => 'Membuat ecobrick',
            'user_id' => $users[1]->id,
            'nama_anggota' => $users[1]->name,
            'status' => 'selesai',
            'file_tugas' => 'dokumentasi_ecobrick.pdf'
        ],
        [
            'tugas' => 'Membuat tas dari plastik',
            'user_id' => $users[2]->id,
            'nama_anggota' => $users[2]->name,
            'status' => 'selesai',
            'file_tugas' => 'dokumentasi_tas.pdf'
        ],
    ]),
    'status_validasi' => 'valid',
    'feedback_guru' => 'Hasil karya sangat kreatif dan berkualitas baik. Ecobrick yang dibuat cukup padat dan rapi.'
]);

// Cek relasi
$sintaks->load('tahapLima');
echo "✅ Relasi SintaksBaru->tahapLima: " . ($sintaks->tahapLima ? "Berhasil" : "Gagal") . "\n";
echo "   - Deskripsi karya: {$sintaks->tahapLima->deskripsi_karya}\n";
echo "   - Status validasi: {$sintaks->tahapLima->status_validasi}\n";
echo "   - Feedback guru: {$sintaks->tahapLima->feedback_guru}\n";

// 8. Tes Relasi ke Tahap 6: Presentasi Proyek
$tahap6 = App\Models\SintaksTahapEnam::create([
    'sintaks_id' => $sintaks->id,
    'link_presentasi' => 'https://meet.google.com/abc-defg-hij',
    'tanggal_presentasi' => '2025-06-25 10:00:00',
    'file_presentasi' => 'presentasi_proyek_daur_ulang.pptx',
    'catatan_presentasi' => 'Presentasi dilakukan secara online dengan durasi 30 menit disertai sesi tanya jawab.',
    'status_validasi' => 'valid',
    'feedback_guru' => 'Presentasi sangat informatif dan menarik. Tim berhasil menjawab semua pertanyaan dengan baik.'
]);

// Cek relasi
$sintaks->load('tahapEnam');
echo "✅ Relasi SintaksBaru->tahapEnam: " . ($sintaks->tahapEnam ? "Berhasil" : "Gagal") . "\n";
echo "   - Link presentasi: {$sintaks->tahapEnam->link_presentasi}\n";
echo "   - Status validasi: {$sintaks->tahapEnam->status_validasi}\n";
echo "   - Feedback guru: {$sintaks->tahapEnam->feedback_guru}\n";

// 9. Tes Relasi ke Tahap 7: Penilaian
$tahap7 = App\Models\SintaksTahapTuju::create([
    'sintaks_id' => $sintaks->id,
    'status_validasi' => 'valid',
    'feedback_guru' => 'Semua anggota kelompok berpartisipasi aktif dalam proyek. Nilai diberikan sesuai kontribusi masing-masing.'
]);

// Cek relasi
$sintaks->load('tahapTuju');
echo "✅ Relasi SintaksBaru->tahapTuju: " . ($sintaks->tahapTuju ? "Berhasil" : "Gagal") . "\n";
echo "   - Status validasi: {$sintaks->tahapTuju->status_validasi}\n";
echo "   - Feedback guru: {$sintaks->tahapTuju->feedback_guru}\n";

// Tambahkan nilai individu untuk semua anggota:
$kriteriaList = [
    ['nama' => 'Kreativitas', 'bobot' => 30],
    ['nama' => 'Kerja sama', 'bobot' => 25],
    ['nama' => 'Kualitas hasil', 'bobot' => 25],
    ['nama' => 'Ketepatan waktu', 'bobot' => 20]
];

foreach ($users as $index => $user) {
    // Buat nilai acak untuk setiap kriteria (1-5)
    $nilaiKriteria = [];
    $totalNilai = 0;
    $totalBobot = 0;
    
    foreach ($kriteriaList as $kriteria) {
        $nilai = rand(3, 5); // Nilai antara 3-5
        $nilaiKriteria[] = [
            'nama' => $kriteria['nama'],
            'nilai' => $nilai,
            'bobot' => $kriteria['bobot']
        ];
        
        $totalNilai += ($nilai * $kriteria['bobot']);
        $totalBobot += $kriteria['bobot'];
    }
    
    $totalNilaiAkhir = round($totalNilai / $totalBobot * 20); // Konversi ke skala 100
    
    $nilaiIndividu = App\Models\SintaksTahapTujuNilai::create([
        'sintaks_penilaian_id' => $tahap7->id,
        'user_id' => $user->id,
        'nilai_kriteria' => json_encode($nilaiKriteria),
        'total_nilai_individu' => $totalNilaiAkhir
    ]);
}

// Cek relasi
$tahap7->load('nilaiIndividu');
echo "✅ Relasi SintaksTahapTuju->nilaiIndividu: " . ($tahap7->nilaiIndividu->count() ? "Berhasil ({$tahap7->nilaiIndividu->count()} nilai)" : "Gagal") . "\n";
echo "   - Jumlah nilai individu: {$tahap7->nilaiIndividu->count()}\n";

// Hitung rata-rata nilai dan update di SintaksBaru
$totalNilaiIndividu = $tahap7->nilaiIndividu->sum('total_nilai_individu');
$avgNilai = round($totalNilaiIndividu / $tahap7->nilaiIndividu->count());
$sintaks->total_nilai = $avgNilai;
$sintaks->save();

echo "   - Rata-rata nilai kelompok: {$sintaks->total_nilai}\n";

// 10. Tes Relasi ke Tahap 8: Evaluasi dan Refleksi
$tahap8 = App\Models\SintaksTahapDelapan::create([
    'sintaks_id' => $sintaks->id,
    'refleksi_kelompok' => 'Selama mengerjakan proyek, kelompok mengalami beberapa tantangan terutama dalam pengumpulan bahan plastik yang cukup untuk membuat semua produk. Namun, dengan kerjasama tim yang baik, kami berhasil menyelesaikan proyek tepat waktu.',
    'pembelajaran' => 'Kelompok belajar bahwa koordinasi dan komunikasi yang baik sangat penting dalam menyelesaikan proyek. Kami juga belajar bahwa sampah plastik dapat diubah menjadi produk yang berguna dengan kreativitas.',
    'status_validasi' => 'valid',
    'feedback_guru' => 'Refleksi kelompok sangat baik dan mencerminkan pembelajaran yang didapat. Kelompok berhasil mengidentifikasi kendala dan solusinya.'
]);

// Cek relasi
$sintaks->load('tahapDelapan');
echo "✅ Relasi SintaksBaru->tahapDelapan: " . ($sintaks->tahapDelapan ? "Berhasil" : "Gagal") . "\n";
echo "   - Refleksi kelompok: " . substr($sintaks->tahapDelapan->refleksi_kelompok, 0, 50) . "...\n";
echo "   - Status validasi: {$sintaks->tahapDelapan->status_validasi}\n";
echo "   - Feedback guru: {$sintaks->tahapDelapan->feedback_guru}\n";

// Tambahkan refleksi individu untuk semua anggota:
foreach ($users as $index => $user) {
    $kendalaList = [
        'Kesulitan mengumpulkan botol plastik yang bersih',
        'Tantangan dalam koordinasi waktu dengan anggota lain',
        'Proses pembuatan ecobrick yang membutuhkan ketelitian',
        'Kesulitan dalam mendesain tas yang menarik dari plastik',
        'Keterbatasan alat untuk mengolah plastik'
    ];
    
    $pembelajaranList = [
        'Belajar teknik daur ulang plastik yang efektif',
        'Meningkatkan keterampilan komunikasi dan kerja tim',
        'Mengenali berbagai jenis plastik dan penggunaannya',
        'Belajar manajemen waktu yang lebih baik',
        'Meningkatkan kreativitas dalam mendaur ulang material'
    ];
    
    $refleksi = App\Models\SintaksTahapDelapanRefleksi::create([
        'sintaks_evaluasi_id' => $tahap8->id,
        'user_id' => $user->id,
        'kendala' => $kendalaList[$index],
        'pembelajaran' => $pembelajaranList[$index]
    ]);
}

// Cek relasi
$tahap8->load('refleksiIndividu');
echo "✅ Relasi SintaksTahapDelapan->refleksiIndividu: " . ($tahap8->refleksiIndividu->count() ? "Berhasil ({$tahap8->refleksiIndividu->count()} refleksi)" : "Gagal") . "\n";
echo "   - Jumlah refleksi individu: {$tahap8->refleksiIndividu->count()}\n";

echo "\n🔍 Pengetesan Komprehensif SintaksBaru dengan status validasi dan feedback:\n";
$sintaks->refresh()->load([
    'kelompok.anggotas.user',
    'materi',
    'tahapSatu', 
    'tahapDua', 
    'tahapTiga', 
    'tahapEmpat.tasks', 
    'tahapLima', 
    'tahapEnam', 
    'tahapTuju.nilaiIndividu', 
    'tahapDelapan.refleksiIndividu'
]);

echo "✅ Struktur sintaks lengkap:\n";
echo "   - Kelompok: {$sintaks->kelompok->kelompok} ({$sintaks->kelompok->anggotas->count()} anggota)\n";
echo "   - Materi: {$sintaks->materi->judul}\n";
echo "   - Status validasi sintaks: {$sintaks->status_validasi}\n";
echo "   - Total nilai: {$sintaks->total_nilai}\n";
echo "   - Tahap 1 (Orientasi): Status={$sintaks->tahapSatu->status_validasi}\n";
echo "   - Tahap 2 (Rancangan): Status={$sintaks->tahapDua->status_validasi}\n";
echo "   - Tahap 3 (Jadwal): Status={$sintaks->tahapTiga->status_validasi}\n";
echo "   - Tahap 4 (Pelaksanaan): Status={$sintaks->tahapEmpat->status_validasi}, {$sintaks->tahapEmpat->tasks->count()} tugas\n";
echo "   - Tahap 5 (Hasil Karya): Status={$sintaks->tahapLima->status_validasi}\n";
echo "   - Tahap 6 (Presentasi): Status={$sintaks->tahapEnam->status_validasi}\n";
echo "   - Tahap 7 (Penilaian): Status={$sintaks->tahapTuju->status_validasi}, {$sintaks->tahapTuju->nilaiIndividu->count()} nilai individu\n";
echo "   - Tahap 8 (Evaluasi): Status={$sintaks->tahapDelapan->status_validasi}, {$sintaks->tahapDelapan->refleksiIndividu->count()} refleksi individu\n";

echo "\n✅ Pengetesan selesai! Semua relasi dan struktur dengan status validasi dan feedback berhasil dibuat.\n"; 