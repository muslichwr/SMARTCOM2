<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SintaksTahapEnam extends Model
{
    use HasFactory;

    protected $table = 'sintaks_tahap_enams';

    protected $fillable = [
        'sintaks_id',
        'link_presentasi',
        'jadwal_presentasi',
        'catatan_presentasi',
        'status',
        'feedback_guru',
        'status_validasi'
    ];

    protected $casts = [
        'jadwal_presentasi' => 'datetime',
    ];

    public function sintaks()
    {
        return $this->belongsTo(SintaksBaru::class);
    }
    
}
