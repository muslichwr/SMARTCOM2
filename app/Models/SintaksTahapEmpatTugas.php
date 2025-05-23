<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SintaksTahapEmpatTugas extends Model
{
    use HasFactory;

    protected $table = 'sintaks_tahap_empat_tugas';

    protected $fillable = [
        'sintaks_pelaksanaan_id',
        'user_id',
        'judul_task',
        'deskripsi_task',
        'deadline',
        'status',
        'catatan',
        'file_tugas'
    ];

    protected $casts = [
        'deadline' => 'date',
    ];

    public function sintaksPelaksanaan()
    {
        return $this->belongsTo(SintaksTahapEmpat::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
