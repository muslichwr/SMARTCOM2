<?php

namespace Database\Factories;

use App\Models\SintaksTahapDelapanRefleksi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SintaksTahapDelapanRefleksi>
 */
class SintaksTahapDelapanRefleksiFactory extends Factory
{
    protected $model = SintaksTahapDelapanRefleksi::class;

    public function definition()
    {
        return [
            'sintaks_evaluasi_id' => null,
            'user_id' => null,
            'refleksi_pribadi' => $this->faker->paragraph,
            'kendala_dihadapi' => $this->faker->sentence,
            'pembelajaran_didapat' => $this->faker->sentence,
        ];
    }
}
