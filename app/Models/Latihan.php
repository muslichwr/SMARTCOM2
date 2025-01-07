<?php

namespace App\Models;

use App\Models\Materi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Latihan extends Model
{
    use HasFactory;

    protected $table = 'latihans';

    protected $fillable = [
        'materi_id',
        'judulLatihan',
        'slug',
        'status',
    ];

    public function materi()
    {
        return $this->belongsTo(Materi::class, 'materi_id', 'id');
    }

    public function getTest()
    {
        return $this->hasMany(Test::class, 'latihan_id', 'id');
    }
}
