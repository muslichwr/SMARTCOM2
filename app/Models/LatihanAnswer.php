<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LatihanAnswer extends Model
{
    use HasFactory;

    protected $table = 'latihans_answer';

    protected $fillable = [
        'latihan_attempt_id',
        'soal_id',
        'typed_answer'
    ];
}
