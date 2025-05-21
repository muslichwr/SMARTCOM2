<?php

namespace Database\Factories;

use App\Models\SintaksTahapSatu;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SintaksTahapSatu>
 */
class SintaksTahapSatuFactory extends Factory
{
    protected $model = SintaksTahapSatu::class;

    public function definition()
    {
        return [
            'sintaks_id' => null,
            'orientasi_masalah' => $this->faker->sentence,
            'rumusan_masalah' => $this->faker->paragraph,
            'file_indikator_masalah' => 'indikator.pdf',
            'file_hasil_analisis' => 'hasil.pdf',
            'status' => 'belum_mulai',
        ];
    }
}
