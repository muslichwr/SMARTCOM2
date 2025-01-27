<?php

namespace App\Models;

use App\Models\User;
use App\Models\PrePost;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PreTestAttempt extends Model
{
    use HasFactory;

    public $table = 'pre_tests_attempt';

    protected $fillable = [
        'pre_post_id',
        'user_id',
        'status',
        'total_nilai'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function prepost()
    {
        return $this->hasOne(PrePost::class, 'id', 'pre_post_id');
    }

    public function answers()
    {
        return $this->hasMany(PreTestAnswer::class, 'pre_test_attempt_id');
    }
}
