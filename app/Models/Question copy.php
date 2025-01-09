<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Tambahkan namespace ini
use Illuminate\Database\Eloquent\Relations\HasMany;  // Tambahkan ini juga jika belum ada

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'question_type_id', 'media_url', 'media_type', 'is_active'];

    public function type(): BelongsTo
    {
        return $this->belongsTo(QuestionType::class, 'question_type_id');
    }

    public function options(): HasMany
    {
        return $this->hasMany(QuestionOption::class);
    }
}