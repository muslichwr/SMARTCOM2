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
        'status'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function prepost()
    {
        return $this->hasOne(PrePost::class, 'id', 'pre_post_id');
    }
}
