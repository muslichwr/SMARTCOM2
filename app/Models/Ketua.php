<?php

namespace App\Models;

use App\Models\User;
use App\Models\Kelompok;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ketua extends Model
{
    use HasFactory;

    protected $table = 'ketuas';

    protected $fillable = [
        'user_id',
        'kelompok_id',
    ];

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
