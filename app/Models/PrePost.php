<?php

namespace App\Models;

use App\Models\Materi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PrePost extends Model
{
    use HasFactory;

    protected $table = 'pre_posts';

    protected $fillable = [
        'materi_id',
        'judulPrePost',
        'slug',
    ];

    public function materi()
    {
        return $this->belongsTo(Materi::class, 'materi_id', 'id');
    }

    public function getPrePostTest()
    {
        return $this->hasMany(PrePostTest::class, 'pre_post_id', 'id');
    }
}
