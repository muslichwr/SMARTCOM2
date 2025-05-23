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
        'status_validasi',
        'feedback_guru'
    ];

    public function sintaks()
    {
        return $this->belongsTo(SintaksBaru::class, 'sintaks_id');
    }

    public function tasks()
    {
        return $this->hasMany(SintaksTahapEmpatTugas::class, 'sintaks_pelaksanaan_id');
    }

    // Alias untuk kompatibilitas dengan kode lama
    public function sintaksTugas()
    {
        return $this->tasks();
    }
}
