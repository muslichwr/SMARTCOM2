<?php

namespace App\Models;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Test extends Model
{
    use HasFactory;

    protected $table = 'tests';

    protected $fillable = [
        'latihan_id',
        'soal_id',
    ];

    public function question()
    {
        return $this->hasMany(Question::class, 'id', 'soal_id');
    }

    //! Perlu Diperhatikan
    public function jawaban()
    {
        return $this->hasMany(Answer::class, 'soal_id', 'soal_id');
    }
}
