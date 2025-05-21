<?php

namespace Database\Factories;

use App\Models\SintaksTahapDelapan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SintaksTahapDelapan>
 */
class SintaksTahapDelapanFactory extends Factory
{
    protected $model = SintaksTahapDelapan::class;

    public function definition()
    {
        return [
            'sintaks_id' => null,
            'evaluasi_kelompok' => $this->faker->paragraph,
            'refleksi_pembelajaran' => $this->faker->paragraph,
            'status' => 'belum_mulai',
        ];
    }
}