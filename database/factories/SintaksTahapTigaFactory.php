<?php

namespace Database\Factories;

use App\Models\SintaksTahapTiga;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SintaksTahapTiga>
 */
class SintaksTahapTigaFactory extends Factory
{
    protected $model = SintaksTahapTiga::class;

    public function definition()
    {
        return [
            'sintaks_id' => null,
            'file_jadwal' => 'jadwal.xlsx',
            'tanggal_mulai' => now(),
            'tanggal_selesai' => now()->addDays(7),
            'status' => 'belum_mulai',
        ];
    }
}