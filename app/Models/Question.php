<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $table = 'soals';

    protected $fillable = [
        'question',
    ];

    public function jawaban()
    {
        return $this->hasOne(Answer::class, 'soal_id', 'id');
    }
}
