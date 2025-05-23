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

    public function anggotas()
    {
        return $this->hasMany(Anggota::class);
    }

    public function ketua()
    {
        return $this->hasOne(Ketua::class);
    }

    public function ketuaUser()
    {
        return $this->hasOneThrough(User::class, Ketua::class, 'kelompok_id', 'id', 'id', 'user_id');
    }

    public function sintaks()
    {
        return $this->hasMany(SintaksBaru::class);
    }

}
