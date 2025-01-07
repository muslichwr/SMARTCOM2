<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $table = 'jawabans';

    protected $fillable = [
        'soal_id',
        'answer',
    ];

    public function soal()
    {
        return $this->belongsTo(Question::class, 'soal_id', 'id');
    }
}
