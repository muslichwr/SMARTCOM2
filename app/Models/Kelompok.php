<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    use HasFactory;

    protected $table = 'kelompoks';

    protected $fillable = [
        'kelompok',
        'slug',
    ];

    public function anggota()
    {
        return $this->hasMany(Anggota::class, 'kelompok_id', 'id');
    }
}
