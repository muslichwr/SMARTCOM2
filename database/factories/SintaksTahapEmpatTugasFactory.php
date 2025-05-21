<?php

namespace Database\Factories;

use App\Models\SintaksTahapEmpatTugas;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SintaksTahapEmpatTugas>
 */
class SintaksTahapEmpatTugasFactory extends Factory
{
    protected $model = SintaksTahapEmpatTugas::class;

    public function definition()
    {
        return [
            'sintaks_pelaksanaan_id' => null,
            'user_id' => null,
            'judul_task' => $this->faker->sentence,
            'deskripsi_task' => $this->faker->paragraph,
            'deadline' => now()->addWeek(),
            'status' => 'belum_mulai',
        ];
    }
}