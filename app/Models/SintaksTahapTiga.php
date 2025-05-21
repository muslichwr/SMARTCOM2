<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SintaksTahapTiga extends Model
{
    use HasFactory;

    protected $table = 'sintaks_tahap_tigas';

    protected $fillable = [
        'sintaks_id',
        'file_jadwal',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'feedback_guru',
        'status_validasi'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function sintaks()
    {
        return $this->belongsTo(SintaksBaru::class);
    }
    
}
