<?php

namespace Database\Factories;

use App\Models\SintaksTahapLima;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SintaksTahapLima>
 */
class SintaksTahapLimaFactory extends Factory
{
    protected $model = SintaksTahapLima::class;

    public function definition()
    {
        return [
            'sintaks_id' => null,
            'file_hasil_karya' => 'hasil_proyek.zip',
            'deskripsi_hasil' => $this->faker->paragraph,
            'status' => 'belum_mulai',
        ];
    }
}
