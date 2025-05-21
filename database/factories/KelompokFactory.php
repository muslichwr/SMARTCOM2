<?php

namespace Database\Factories;

use App\Models\Kelompok;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class KelompokFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Kelompok::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $kelompok = 'Kelompok ' . $this->faker->unique()->numberBetween(1, 100);
        return [
            'kelompok' => $kelompok,
            'slug' => Str::slug($kelompok),
        ];
    }
} 