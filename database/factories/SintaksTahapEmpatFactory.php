<?php

namespace Database\Factories;

use App\Models\SintaksTahapEmpat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SintaksTahapEmpat>
 */
class SintaksTahapEmpatFactory extends Factory
{
    protected $model = SintaksTahapEmpat::class;

    public function definition()
    {
        return [
            'sintaks_id' => null,
            'status' => 'belum_mulai',
        ];
    }
}
