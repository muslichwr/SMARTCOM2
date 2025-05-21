<?php

namespace Database\Factories;

use App\Models\SintaksTahapEnam;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SintaksTahapEnam>
 */
class SintaksTahapEnamFactory extends Factory
{
    protected $model = SintaksTahapEnam::class;

    public function definition()
    {
        return [
            'sintaks_id' => null,
            'link_presentasi' => 'https://meet.google.com/ ' . $this->faker->randomLetter . $this->faker->randomNumber(3),
            'jadwal_presentasi' => now()->addDays(10),
            'catatan_presentasi' => $this->faker->paragraph,
            'status' => 'belum_mulai',
        ];
    }
}