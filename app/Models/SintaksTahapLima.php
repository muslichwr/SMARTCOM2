<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SintaksTahapLima extends Model
{
    use HasFactory;

    protected $table = 'sintaks_tahap_limas';

    protected $fillable = [
        'sintaks_id',
        'file_hasil_karya',
        'deskripsi_hasil',
        'status',
        'feedback_guru',
        'status_validasi'
    ];

    public function sintaks()
    {
        return $this->belongsTo(SintaksBaru::class);
    }
    
}
