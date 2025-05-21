<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SintaksTahapTuju;

class SintaksTahapTujuNilai extends Model
{
    use HasFactory;

    protected $table = 'sintaks_tahap_tuju_nilais';

    protected $fillable = [
        'sintaks_penilaian_id',
        'user_id',
        'nilai_kriteria',
        'total_nilai_individu',

    ];

    protected $casts = [
        'nilai_kriteria' => 'array',
    ];

    public function penilaian()
    {
        return $this->belongsTo(SintaksTahapTuju::class, 'sintaks_penilaian_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hitungTotalNilai()
    {
        $kriteria = $this->nilai_kriteria['kriteria'] ?? [];
        $totalNilaiTertimbang = 0;
        $totalBobot = 0;
        
        foreach ($kriteria as $item) {
            $totalNilaiTertimbang += $item['nilai_tertimbang'] ?? 0;
            $totalBobot += $item['bobot'] ?? 0;
        }
        
        // Hitung nilai akhir (skala 0-100)
        $totalNilai = ($totalBobot > 0) ? ($totalNilaiTertimbang / $totalBobot) * 100 : 0;
        
        // Update field total
        $this->total_nilai_individu = round($totalNilai, 1);
        $this->save();
        
        return $this->total_nilai_individu;
    }
}
