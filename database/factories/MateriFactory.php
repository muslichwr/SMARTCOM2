<?php

namespace Database\Factories;

use App\Models\Materi;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MateriFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Materi::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $judul = $this->faker->sentence(3);
        return [
            'judul' => $judul,
            'slug' => Str::slug($judul),
            'deskripsi' => $this->faker->paragraph,
            'pjbl_sintaks_active' => true,
        ];
    }
} 