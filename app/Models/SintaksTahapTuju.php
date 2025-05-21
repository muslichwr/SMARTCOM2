<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SintaksTahapTuju extends Model
{
    use HasFactory;
    
    protected $table = 'sintaks_tahap_tujus';
    
    protected $fillable = [
        'sintaks_id',
        'status',
        'feedback_guru',
        'status_validasi',
        'feedback_guru',
        'status_validasi'
    ];
    
    public function sintaks()
    {
        return $this->belongsTo(SintaksBaru::class);
    }
    
    public function nilaiIndividu()
    {
        return $this->hasMany(SintaksTahapTujuNilai::class, 'sintaks_penilaian_id');
    }
}
