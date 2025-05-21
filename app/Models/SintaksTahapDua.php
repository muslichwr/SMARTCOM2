<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SintaksTahapDua extends Model
{
    use HasFactory;

    protected $table = 'sintaks_tahap_duas';

    protected $fillable = [
        'sintaks_id',
        'file_rancangan',
        'deskripsi_rancangan',
        'status',
        'feedback_guru',
        'status_validasi'
    ];

    public function sintaks()
    {
        return $this->belongsTo(SintaksBaru::class);
    }
    
}