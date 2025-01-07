<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LatihanAttempt extends Model
{
    use HasFactory;

    public $table = 'latihans_attempt';

    protected $fillable = [
        'latihan_id',
        'user_id',
        'status'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function latihan()
    {
        return $this->hasOne(Latihan::class, 'id', 'latihan_id');
    }
}
