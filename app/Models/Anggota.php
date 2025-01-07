<?php

namespace App\Models;

use App\Models\User;
use App\Models\Kelompok;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Anggota extends Model
{
    use HasFactory;

    protected $table = 'anggotas';

    protected $fillable = [
        'user_id',
        'kelompok_id',
    ];

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'kelompok_id', 'id');
    }

    public function users()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

}
