<?php

namespace App\Models;

use App\Models\Materi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bab extends Model
{
    use HasFactory;

    protected $table = 'babs';

    protected $fillable = [
        'materi_id',
        'judul',
        'slug',
        'isi',
        'status'
    ];

    public function materi()
    {
        return $this->belongsTo(Materi::class, 'materi_id', 'id');
    }
}
