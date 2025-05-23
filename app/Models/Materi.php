<?php

namespace App\Models;

use App\Models\Bab;
use App\Models\Latihan;
use App\Models\PrePost;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Materi extends Model
{
    use HasFactory;

    protected $table = 'materis';

    protected $fillable = [
        'judul',
        'slug',
        'deskripsi',
        'pjbl_sintaks_active'
    ];

    protected $casts = [
        'pjbl_sintaks_active' => 'boolean',
    ];

    public function babs()
    {
        return $this->hasMany(Bab::class, 'materi_id', 'id');
    }

    public function latihans()
    {
        return $this->hasMany(Latihan::class, 'materi_id', 'id');
    }

    public function preposts()
    {
        return $this->hasMany(PrePost::class, 'materi_id', 'id');
    }

    // Tambahkan relasi sintaks
    public function sintaks()
    {
        return $this->hasMany(SintaksBaru::class, 'materi_id', 'id');
    }
}