<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SintaksTahapDelapanRefleksi extends Model
{
    use HasFactory;

    protected $table = 'sintaks_tahap_delapan_refleksis';

    protected $fillable = [
        'sintaks_evaluasi_id',
        'user_id',
        'refleksi_pribadi',
        'kendala_dihadapi',
        'pembelajaran_didapat'
    ];

    public function evaluasi()
    {
        return $this->belongsTo(SintaksTahapDelapan::class, 'sintaks_evaluasi_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    } 
}
