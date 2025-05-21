<?php

namespace Database\Factories;

use App\Models\SintaksTahapDua;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SintaksTahapDua>
 */
class SintaksTahapDuaFactory extends Factory
{
    protected $model = SintaksTahapDua::class;

    public function definition()
    {
        return [
            'sintaks_id' => null,
            'file_rancangan' => 'rancangan.xlsx',
            'deskripsi_rancangan' => $this->faker->paragraph,
            'status' => 'belum_mulai',
        ];
    }
}
