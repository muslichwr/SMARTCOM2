<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sintaks extends Model
{
    use HasFactory;

    protected $table = 'sintaks';

    protected $fillable = [
        'materi_id',
        'kelompok_id',
        'status_tahap',
        'orientasi_masalah',
        'rumusan_masalah',
        'indikator_masalah',
        'hasil_analisis',
        'deskripsi_proyek',
        'tugas_anggota',
        'file_jadwal', // Tambahkan ini
        'to_do_list',
        'file_proyek',
        'file_laporan',
        'status_validasi',
        'feedback_guru',
        'score_class_object',
        'score_encapsulation',
        'score_inheritance',
        'score_logic_function',
        'score_project_report',
    ];

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class);
    }

    public function materi()
    {
        return $this->belongsTo(Materi::class);
    }

    public function getTahapAttribute()
    {
        switch ($this->status_tahap) {
            case 'tahap_1':
                return 'Orientasi Masalah & Rumusan Masalah';
            case 'tahap_2':
                return 'Indikator Masalah & Hasil Analisis';
            case 'tahap_3':
                return 'Deskripsi Proyek & Tugas Anggota';
            default:
                return 'Tahap Tidak Diketahui';
        }
    }
}
