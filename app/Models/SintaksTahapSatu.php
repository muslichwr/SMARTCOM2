<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SintaksTahapSatu extends Model
{
    use HasFactory;

    protected $table = 'sintaks_tahap_satus';

    protected $fillable = [
        'sintaks_id',
        'orientasi_masalah',
        'rumusan_masalah',
        'file_indikator_masalah',
        'file_hasil_analisis',
        'status',
        'feedback_guru',
        'status_validasi'
    ];

    public function sintaks()
    {
        return $this->belongsTo(SintaksBaru::class);
    }

}
