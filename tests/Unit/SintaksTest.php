<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\SintaksBaru;
use App\Models\Kelompok;
use App\Models\Materi;
use App\Models\SintaksTahapSatu;
use App\Models\SintaksTahapDua;
use App\Models\SintaksTahapTiga;
use App\Models\SintaksTahapEmpat;
use App\Models\SintaksTahapEmpatTugas;
use App\Models\SintaksTahapLima;
use App\Models\SintaksTahapEnam;
use App\Models\SintaksTahapTuju;
use App\Models\SintaksTahapTujuNilai;
use App\Models\SintaksTahapDelapan;
use App\Models\SintaksTahapDelapanRefleksi;
use App\Models\User;

class SintaksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function sintaks_baru_dapat_dibuat_dan_relasi_berjalan()
    {
        $kelompok = Kelompok::factory()->create();
        $materi = Materi::factory()->create();

        $sintaks = SintaksBaru::create([
            'kelompok_id' => $kelompok->id,
            'materi_id' => $materi->id,
            'status_validasi' => 'pending',
            'feedback_guru' => null,
            'total_nilai' => null,
        ]);

        $this->assertNotNull($sintaks);
        $this->assertInstanceOf(SintaksBaru::class, $sintaks);
        $this->assertEquals('pending', $sintaks->status_validasi);
        $this->assertTrue($sintaks->kelompok->is($kelompok));
        $this->assertTrue($sintaks->materi->is($materi));
    }

    /** @test */
    public function tahap_satu_dapat_dibuat_dengan_relasi_ke_sintaks()
    {
        $sintaks = SintaksBaru::factory()->create();

        $tahapSatu = SintaksTahapSatu::create([
            'sintaks_id' => $sintaks->id,
            'orientasi_masalah' => 'Orientasi masalah',
            'rumusan_masalah' => 'Rumusan masalah',
            'file_indikator_masalah' => 'file1.pdf',
            'file_hasil_analisis' => 'file2.pdf',
            'status' => 'proses'
        ]);

        $this->assertNotNull($tahapSatu);
        $this->assertInstanceOf(SintaksTahapSatu::class, $tahapSatu);
        $this->assertTrue($tahapSatu->sintaks->is($sintaks));
        $this->assertEquals('proses', $tahapSatu->status);
    }

    /** @test */
    public function tahap_dua_dapat_dibuat_dengan_relasi_ke_sintaks()
    {
        $sintaks = SintaksBaru::factory()->create();

        $tahapDua = SintaksTahapDua::create([
            'sintaks_id' => $sintaks->id,
            'file_rancangan' => 'rancangan.xlsx',
            'deskripsi_rancangan' => 'Deskripsi rancangan',
            'status' => 'selesai'
        ]);

        $this->assertInstanceOf(SintaksTahapDua::class, $tahapDua);
        $this->assertTrue($tahapDua->sintaks->is($sintaks));
        $this->assertEquals('selesai', $tahapDua->status);
    }

    /** @test */
    public function tahap_tiga_dapat_dibuat_dengan_relasi_ke_sintaks()
    {
        $sintaks = SintaksBaru::factory()->create();

        $tahapTiga = SintaksTahapTiga::create([
            'sintaks_id' => $sintaks->id,
            'file_jadwal' => 'jadwal.xlsx',
            'tanggal_mulai' => '2025-01-01',
            'tanggal_selesai' => '2025-01-10',
            'status' => 'belum_mulai'
        ]);

        $this->assertInstanceOf(SintaksTahapTiga::class, $tahapTiga);
        $this->assertTrue($tahapTiga->sintaks->is($sintaks));
        $this->assertEquals('2025-01-01', $tahapTiga->tanggal_mulai);
    }

    /** @test */
    public function tahap_empat_dapat_dibuat_dengan_relasi_ke_sintaks()
    {
        $sintaks = SintaksBaru::factory()->create();

        $tahapEmpat = SintaksTahapEmpat::create([
            'sintaks_id' => $sintaks->id,
            'status' => 'proses'
        ]);

        $this->assertInstanceOf(SintaksTahapEmpat::class, $tahapEmpat);
        $this->assertTrue($tahapEmpat->sintaks->is($sintaks));
    }

    /** @test */
    public function tugas_pelaksanaan_dapat_dibuat_dengan_relasi_ke_tugas_dan_user()
    {
        $user = User::factory()->create();
        $tahapEmpat = SintaksTahapEmpat::factory()->create();

        $tugas = SintaksTahapEmpatTugas::create([
            'sintaks_pelaksanaan_id' => $tahapEmpat->id,
            'user_id' => $user->id,
            'judul_task' => 'Tugas pertama',
            'deskripsi_task' => 'Deskripsi tugas',
            'deadline' => '2025-01-05',
            'status' => 'proses'
        ]);

        $this->assertInstanceOf(SintaksTahapEmpatTugas::class, $tugas);
        $this->assertTrue($tugas->pelaksanaanProyek->is($tahapEmpat));
        $this->assertTrue($tugas->user->is($user));
        $this->assertEquals('proses', $tugas->status);
    }

    /** @test */
    public function tahap_lima_dapat_dibuat_dengan_relasi_ke_sintaks()
    {
        $sintaks = SintaksBaru::factory()->create();

        $tahapLima = SintaksTahapLima::create([
            'sintaks_id' => $sintaks->id,
            'file_hasil_karya' => 'hasil.zip',
            'deskripsi_hasil' => 'Hasil akhir proyek',
            'status' => 'selesai'
        ]);

        $this->assertInstanceOf(SintaksTahapLima::class, $tahapLima);
        $this->assertTrue($tahapLima->sintaks->is($sintaks));
    }

    /** @test */
    public function tahap_enam_dapat_dibuat_dengan_relasi_ke_sintaks()
    {
        $sintaks = SintaksBaru::factory()->create();

        $tahapEnam = SintaksTahapEnam::create([
            'sintaks_id' => $sintaks->id,
            'link_presentasi' => 'https://meet.google.com/abc123 ',
            'jadwal_presentasi' => '2025-01-15 10:00:00',
            'catatan_presentasi' => 'Presentasi berhasil',
            'status' => 'selesai'
        ]);

        $this->assertInstanceOf(SintaksTahapEnam::class, $tahapEnam);
        $this->assertTrue($tahapEnam->sintaks->is($sintaks));
    }

    /** @test */
    public function tahap_tuju_dapat_dibuat_dengan_relasi_ke_sintaks()
    {
        $sintaks = SintaksBaru::factory()->create();

        $tahapTuju = SintaksTahapTuju::create([
            'sintaks_id' => $sintaks->id,
            'status' => 'selesai'
        ]);

        $this->assertInstanceOf(SintaksTahapTuju::class, $tahapTuju);
        $this->assertTrue($tahapTuju->sintaks->is($sintaks));
    }

    /** @test */
    public function nilai_individu_dapat_dibuat_dengan_relasi_ke_penilaian_dan_user()
    {
        $user = User::factory()->create();
        $tahapTuju = SintaksTahapTuju::factory()->create();

        $nilaiIndividu = SintaksTahapTujuNilai::create([
            'sintaks_penilaian_id' => $tahapTuju->id,
            'user_id' => $user->id,
            'nilai_kriteria' => ['kriteria' => [['nilai_tertimbang' => 4, 'bobot' => 2]]],
            'total_nilai_individu' => 80,
        ]);

        $this->assertInstanceOf(SintaksTahapTujuNilai::class, $nilaiIndividu);
        $this->assertTrue($nilaiIndividu->penilaian->is($tahapTuju));
        $this->assertTrue($nilaiIndividu->user->is($user));
    }

    /** @test */
    public function tahap_delapan_dapat_dibuat_dengan_relasi_ke_sintaks()
    {
        $sintaks = SintaksBaru::factory()->create();

        $tahapDelapan = SintaksTahapDelapan::create([
            'sintaks_id' => $sintaks->id,
            'evaluasi_kelompok' => 'Evaluasi kelompok berhasil',
            'refleksi_pembelajaran' => 'Pembelajaran yang bermakna',
            'status' => 'selesai'
        ]);

        $this->assertInstanceOf(SintaksTahapDelapan::class, $tahapDelapan);
        $this->assertTrue($tahapDelapan->sintaks->is($sintaks));
    }

    /** @test */
    public function refleksi_individu_dapat_dibuat_dengan_relasi_ke_evaluasi_dan_user()
    {
        $user = User::factory()->create();
        $tahapDelapan = SintaksTahapDelapan::factory()->create();

        $refleksi = SintaksTahapDelapanRefleksi::create([
            'sintaks_evaluasi_id' => $tahapDelapan->id,
            'user_id' => $user->id,
            'refleksi_pribadi' => 'Saya belajar banyak hal.',
            'kendala_dihadapi' => 'Internet lambat',
            'pembelajaran_didapat' => 'Kerja tim penting',
        ]);

        $this->assertInstanceOf(SintaksTahapDelapanRefleksi::class, $refleksi);
        $this->assertTrue($refleksi->evaluasi->is($tahapDelapan));
        $this->assertTrue($refleksi->user->is($user));
    }
}