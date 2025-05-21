<?php

namespace Database\Factories;

use App\Models\SintaksBaru;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SintaksBaru>
 */
class SintaksBaruFactory extends Factory
{
    protected $model = SintaksBaru::class;

    public function definition()
    {
        return [
            'kelompok_id' => null,
            'materi_id' => null,
            'status_validasi' => 'pending',
            'feedback_guru' => null,
            'total_nilai' => null,
        ];
    }
}
