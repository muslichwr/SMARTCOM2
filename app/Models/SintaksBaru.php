<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SintaksBaru extends Model
{
    use HasFactory;

    protected $table = 'sintaks_barus';

    protected $fillable = [
        'kelompok_id',
        'materi_id',
        'status_validasi',
        'feedback_guru',
        'total_nilai',
    ];

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class);
    }

    public function materi()
    {
        return $this->belongsTo(Materi::class);
    }

    public function sintaksTahapSatu()
    {
        return $this->hasOne(SintaksTahapSatu::class, 'sintaks_id');
    }

    public function sintaksTahapDua()
    {
        return $this->hasOne(SintaksTahapDua::class, 'sintaks_id');
    }

    public function sintaksTahapTiga()
    {
        return $this->hasOne(SintaksTahapTiga::class, 'sintaks_id');
    }
    
    public function sintaksTahapEmpat()
    {
        return $this->hasOne(SintaksTahapEmpat::class, 'sintaks_id');
    }

    public function sintaksTahapLima()
    {
        return $this->hasOne(SintaksTahapLima::class, 'sintaks_id');
    }
    
    public function sintaksTahapEnam()
    {
        return $this->hasOne(SintaksTahapEnam::class, 'sintaks_id');
    }

    public function sintaksTahapTuju()
    {
        return $this->hasOne(SintaksTahapTuju::class, 'sintaks_id');
    }

    public function sintaksTahapDelapan()
    {
        return $this->hasOne(SintaksTahapDelapan::class, 'sintaks_id');
    }
    
    // Alias untuk sintaksTahapSatu agar sesuai dengan kode pengetesan
    public function tahapSatu()
    {
        return $this->sintaksTahapSatu();
    }
    
    // Alias untuk sintaksTahapDua agar sesuai dengan kode pengetesan
    public function tahapDua()
    {
        return $this->sintaksTahapDua();
    }
    
    // Alias untuk sintaksTahapTiga agar sesuai dengan kode pengetesan
    public function tahapTiga()
    {
        return $this->sintaksTahapTiga();
    }
    
    // Alias untuk sintaksTahapEmpat agar sesuai dengan kode pengetesan
    public function tahapEmpat()
    {
        return $this->sintaksTahapEmpat();
    }
    
    // Alias untuk sintaksTahapLima agar sesuai dengan kode pengetesan
    public function tahapLima()
    {
        return $this->sintaksTahapLima();
    }
    
    // Alias untuk sintaksTahapEnam agar sesuai dengan kode pengetesan
    public function tahapEnam()
    {
        return $this->sintaksTahapEnam();
    }
    
    // Alias untuk sintaksTahapTuju agar sesuai dengan kode pengetesan
    public function tahapTuju()
    {
        return $this->sintaksTahapTuju();
    }
    
    // Alias untuk sintaksTahapDelapan agar sesuai dengan kode pengetesan
    public function tahapDelapan()
    {
        return $this->sintaksTahapDelapan();
    }
}