<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SintaksTahapEmpat extends Model
{
    use HasFactory;

    protected $table = 'sintaks_tahap_empats';

    protected $fillable = [
        'sintaks_id',
        'status',
                'feedback_guru',
        'status_validasi'
    ];

    public function sintaks()
    {
        return $this->belongsTo(SintaksBaru::class);
    }

    public function sintaksTugas()
    {
        return $this->hasMany(SintaksTahapEmpatTugas::class, 'sintaks_pelaksanaan_id');
    }
    
    // Alias untuk sintaksTugas untuk kecocokan dengan kode pengetesan
    public function tasks()
    {
        return $this->hasMany(SintaksTahapEmpatTugas::class, 'sintaks_pelaksanaan_id');
    }
}
