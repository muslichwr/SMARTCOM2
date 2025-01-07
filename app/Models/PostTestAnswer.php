<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostTestAnswer extends Model
{
    use HasFactory;

    protected $table = 'post_tests_answer';

    protected $fillable = [
        'post_test_attempt_id',
        'soal_id',
        'typed_answer',
    ];
}
