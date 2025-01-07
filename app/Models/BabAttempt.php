<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BabAttempt extends Model
{
    use HasFactory;

    public $table = 'babs_attempt';

    protected $fillable = [
        'bab_id',
        'user_id',
        'status'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function bab()
    {
        return $this->hasOne(Bab::class, 'id', 'bab_id');
    }
}
