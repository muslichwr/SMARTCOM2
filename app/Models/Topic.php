<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; 

class Topic extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'topics';

    protected $fillable = ['name', 'parent_id', 'is_active'];

    protected static function boot()
    {
        parent::boot();
    
        static::saving(function ($topic) {
            if ($topic->isDirty('name')) {
                $slug = Str::slug($topic->name);
                $count = Topic::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();
                $topic->slug = $count ? "{$slug}-{$count}" : $slug;
            }
        });
    }

    public function children()
    {
        return $this->hasMany(Topic::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Topic::class, 'parent_id');
    }

    public function questions()
    {
        return $this->morphedByMany(Question::class, 'topicable');
    }
}
