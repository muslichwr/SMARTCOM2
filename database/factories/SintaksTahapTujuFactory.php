<?php

namespace Database\Factories;

use App\Models\SintaksTahapTuju;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SintaksTahapTuju>
 */
class SintaksTahapTujuFactory extends Factory
{
    protected $model = SintaksTahapTuju::class;

    public function definition()
    {
        return [
            'sintaks_id' => null,
            'status' => 'belum_mulai',
        ];
    }
}
