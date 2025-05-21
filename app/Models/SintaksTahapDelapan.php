<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SintaksTahapDelapan extends Model
{
    use HasFactory;

    protected $table = 'sintaks_tahap_delapans';

    protected $fillable = [
        'sintaks_id',
        'evaluasi_kelompok',
        'refleksi_pembelajaran',
        'status',
        'feedback_guru',
        'status_validasi'
    ];

    public function sintaks()
    {
        return $this->belongsTo(SintaksBaru::class);
    }

    public function refleksi()
    {
        return $this->hasMany(SintaksTahapDelapanRefleksi::class);
    }
    
    // Alias untuk refleksi untuk kecocokan dengan kode pengetesan
    public function refleksiIndividu()
    {
        return $this->hasMany(SintaksTahapDelapanRefleksi::class, 'sintaks_evaluasi_id');
    }
}
