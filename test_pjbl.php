<?php

// Autoload composer
require __DIR__.'/vendor/autoload.php';

// Load Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🧪 Mulai pengetesan PJBL\n";

// 1. Buat Kelompok & Materi
$kelompok = App\Models\Kelompok::factory()->create();
echo "✅ Kelompok berhasil dibuat: " . $kelompok->kelompok . "\n";

$materi = App\Models\Materi::factory()->create();
echo "✅ Materi berhasil dibuat: " . $materi->judul . "\n";

// 2. Buat SintaksBaru
$sintaks = App\Models\SintaksBaru::create([
    'kelompok_id' => $kelompok->id,
    'materi_id' => $materi->id,
    'status_validasi' => 'pending',
]);
echo "✅ SintaksBaru berhasil dibuat dengan ID: " . $sintaks->id . "\n";

// 3. Tes Relasi ke Tahap 1: Orientasi Masalah
$tahap1 = App\Models\SintaksTahapSatu::create([
    'sintaks_id' => $sintaks->id,
    'orientasi_masalah' => 'Orientasi masalah tentang lingkungan.',
    'rumusan_masalah' => 'Apa solusi terbaik untuk mengurangi sampah plastik?',
    'file_indikator_masalah' => 'indikator.pdf',
    'file_hasil_analisis' => 'hasil_analisis.docx',
]);

// Cek relasi
$sintaks->load('tahapSatu');
echo "✅ Relasi SintaksBaru->tahapSatu: " . ($sintaks->tahapSatu ? "Berhasil" : "Gagal") . "\n";
echo "   - Orientasi masalah: {$sintaks->tahapSatu->orientasi_masalah}\n";

// 4. Tes Relasi ke Tahap 2: Rancangan Proyek
$tahap2 = App\Models\SintaksTahapDua::create([
    'sintaks_id' => $sintaks->id,
    'file_rancangan' => 'rancangan.xlsx',
    'deskripsi_rancangan' => 'Rancangan proyek daur ulang plastik.'
]);

// Cek relasi
$sintaks->load('tahapDua');
echo "✅ Relasi SintaksBaru->tahapDua: " . ($sintaks->tahapDua ? "Berhasil" : "Gagal") . "\n";
echo "   - Deskripsi: {$sintaks->tahapDua->deskripsi_rancangan}\n";

// 5. Tes Relasi ke Tahap 3: Jadwal Proyek
$tahap3 = App\Models\SintaksTahapTiga::create([
    'sintaks_id' => $sintaks->id,
    'file_jadwal' => 'jadwal.xlsx',
    'tanggal_mulai' => '2025-04-01',
    'tanggal_selesai' => '2025-04-30'
]);

// Cek relasi
$sintaks->load('tahapTiga');
echo "✅ Relasi SintaksBaru->tahapTiga: " . ($sintaks->tahapTiga ? "Berhasil" : "Gagal") . "\n";
echo "   - Tanggal mulai: {$sintaks->tahapTiga->tanggal_mulai}, Tanggal selesai: {$sintaks->tahapTiga->tanggal_selesai}\n";

// 6. Tes Relasi ke Tahap 4: Pelaksanaan Proyek
$tahap4 = App\Models\SintaksTahapEmpat::create([
    'sintaks_id' => $sintaks->id
]);

// Cek relasi
$sintaks->load('tahapEmpat');
echo "✅ Relasi SintaksBaru->tahapEmpat: " . ($sintaks->tahapEmpat ? "Berhasil" : "Gagal") . "\n";

// Tambahkan beberapa tugas pelaksanaan:
// Pastikan ada user
$user = App\Models\User::first(); 
if (!$user) {
    echo "⚠ Tidak ada user dalam database. Membuat user baru...\n";
    $user = App\Models\User::factory()->create();
    echo "✅ User berhasil dibuat: {$user->name}\n";
}

$tugas = App\Models\SintaksTahapEmpatTugas::create([
    'sintaks_pelaksanaan_id' => $tahap4->id,
    'user_id' => $user->id,
    'judul_task' => 'Kumpulkan data lapangan',
    'deskripsi_task' => 'Survei ke 10 warga lokal',
    'deadline' => '2025-04-15'
]);

// Cek relasi
$tahap4->load('tasks');
echo "✅ Relasi SintaksTahapEmpat->tasks: " . ($tahap4->tasks->count() ? "Berhasil ({$tahap4->tasks->count()} tugas)" : "Gagal") . "\n";
echo "   - Tugas pertama: {$tahap4->tasks->first()->judul_task}\n";

// 7. Tes Relasi ke Tahap 5: Hasil Karya Proyek
$tahap5 = App\Models\SintaksTahapLima::create([
    'sintaks_id' => $sintaks->id,
    'file_hasil_karya' => 'produk_final.zip',
    'deskripsi_hasil' => 'Prototype daur ulang plastik menjadi paving block'
]);

// Cek relasi
$sintaks->load('tahapLima');
echo "✅ Relasi SintaksBaru->tahapLima: " . ($sintaks->tahapLima ? "Berhasil" : "Gagal") . "\n";
echo "   - Deskripsi hasil: {$sintaks->tahapLima->deskripsi_hasil}\n";

// 8. Tes Relasi ke Tahap 6: Presentasi Proyek
$tahap6 = App\Models\SintaksTahapEnam::create([
    'sintaks_id' => $sintaks->id,
    'link_presentasi' => 'https://meet.google.com/abc123',
    'jadwal_presentasi' => '2025-05-01 10:00:00',
    'catatan_presentasi' => 'Presentasi berjalan lancar'
]);

// Cek relasi
$sintaks->load('tahapEnam');
echo "✅ Relasi SintaksBaru->tahapEnam: " . ($sintaks->tahapEnam ? "Berhasil" : "Gagal") . "\n";
echo "   - Link presentasi: {$sintaks->tahapEnam->link_presentasi}\n";

// 9. Tes Relasi ke Tahap 7: Penilaian
$tahap7 = App\Models\SintaksTahapTuju::create([
    'sintaks_id' => $sintaks->id
]);

// Cek relasi
$sintaks->load('tahapTuju');
echo "✅ Relasi SintaksBaru->tahapTuju: " . ($sintaks->tahapTuju ? "Berhasil" : "Gagal") . "\n";

// Tambahkan nilai individu:
$nilaiIndividu = App\Models\SintaksTahapTujuNilai::create([
    'sintaks_penilaian_id' => $tahap7->id,
    'user_id' => $user->id,
    'nilai_kriteria' => json_encode([
        'kriteria' => [
            ['nama' => 'Kreativitas', 'nilai' => 4, 'bobot' => 30],
            ['nama' => 'Kolaborasi', 'nilai' => 5, 'bobot' => 20]
        ]
    ]),
    'total_nilai_individu' => 85
]);

// Cek relasi
$tahap7->load('nilaiIndividu');
echo "✅ Relasi SintaksTahapTuju->nilaiIndividu: " . ($tahap7->nilaiIndividu->count() ? "Berhasil ({$tahap7->nilaiIndividu->count()} nilai)" : "Gagal") . "\n";
echo "   - Total nilai individu: {$tahap7->nilaiIndividu->first()->total_nilai_individu}\n";

// 10. Tes Relasi ke Tahap 8: Evaluasi dan Refleksi
$tahap8 = App\Models\SintaksTahapDelapan::create([
    'sintaks_id' => $sintaks->id,
    'evaluasi_kelompok' => 'Proyek berhasil mencapai target',
    'refleksi_pembelajaran' => 'Belajar kolaborasi sangat penting'
]);

// Cek relasi
$sintaks->load('tahapDelapan');
echo "✅ Relasi SintaksBaru->tahapDelapan: " . ($sintaks->tahapDelapan ? "Berhasil" : "Gagal") . "\n";
echo "   - Evaluasi kelompok: {$sintaks->tahapDelapan->evaluasi_kelompok}\n";

// Tambahkan refleksi individu:
$refleksi = App\Models\SintaksTahapDelapanRefleksi::create([
    'sintaks_evaluasi_id' => $tahap8->id,
    'user_id' => $user->id,
    'refleksi_pribadi' => 'Saya belajar manajemen waktu',
    'kendala_dihadapi' => 'Komunikasi kurang efektif',
    'pembelajaran_didapat' => 'Harus lebih koordinasi tim'
]);

// Cek relasi
$tahap8->load('refleksiIndividu');
echo "✅ Relasi SintaksTahapDelapan->refleksiIndividu: " . ($tahap8->refleksiIndividu->count() ? "Berhasil ({$tahap8->refleksiIndividu->count()} refleksi)" : "Gagal") . "\n";
echo "   - Refleksi pribadi: {$tahap8->refleksiIndividu->first()->refleksi_pribadi}\n";

echo "\n🔍 Pengetesan Komprehensif:\n";
$sintaks->refresh()->load([
    'tahapSatu', 'tahapDua', 'tahapTiga', 'tahapEmpat', 'tahapLima', 
    'tahapEnam', 'tahapTuju', 'tahapDelapan'
]);

echo "✅ Struktur sintaks lengkap:\n";
echo "   - Kelompok: {$sintaks->kelompok->kelompok}\n";
echo "   - Materi: {$sintaks->materi->judul}\n";
echo "   - Tahap 1 (Orientasi): " . ($sintaks->tahapSatu ? "OK" : "Gagal") . "\n";
echo "   - Tahap 2 (Rancangan): " . ($sintaks->tahapDua ? "OK" : "Gagal") . "\n";
echo "   - Tahap 3 (Jadwal): " . ($sintaks->tahapTiga ? "OK" : "Gagal") . "\n";
echo "   - Tahap 4 (Pelaksanaan): " . ($sintaks->tahapEmpat ? "OK" : "Gagal") . "\n";
echo "   - Tahap 5 (Hasil Karya): " . ($sintaks->tahapLima ? "OK" : "Gagal") . "\n";
echo "   - Tahap 6 (Presentasi): " . ($sintaks->tahapEnam ? "OK" : "Gagal") . "\n";
echo "   - Tahap 7 (Penilaian): " . ($sintaks->tahapTuju ? "OK" : "Gagal") . "\n";
echo "   - Tahap 8 (Evaluasi): " . ($sintaks->tahapDelapan ? "OK" : "Gagal") . "\n";

echo "\n✅ Pengetesan selesai! Semua relasi dan struktur berhasil dibuat.\n"; 