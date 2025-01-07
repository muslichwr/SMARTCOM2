<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreTestAnswer extends Model
{
    use HasFactory;

    protected $table = 'pre_tests_answer';

    protected $fillable = [
        'pre_test_attempt_id',
        'soal_id',
        'typed_answer',
        // 'link_github',
    ];
}
