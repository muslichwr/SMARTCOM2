<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionType extends Model
{
    protected $fillable = ['name'];

    public function getDisplayNameAttribute()
    {
        $nameMapping = [
            'multiple_choice_single_answer' => 'Multiple Choice (Single Answer)',
            'multiple_choice_multiple_answer' => 'Multiple Choice (Multiple Answers)',
            'fill_the_blank' => 'Fill in the Blank',
        ];

        return $nameMapping[$this->name] ?? $this->name;
    }
}