<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'total_marks', 'pass_marks', 'max_attempts', 'is_published', 'media_url', 'media_type', 'duration', 'time_between_attempts', 'valid_from', 'valid_upto', 'negative_marking_settings'];

    public function questions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class);
    }

    public function topics(): MorphToMany
    {
        return $this->morphToMany(Topic::class, 'topicable');
    }

    public function authors(): HasMany
    {
        return $this->hasMany(QuizAuthor::class);
    }
}