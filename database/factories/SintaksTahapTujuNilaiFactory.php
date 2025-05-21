<?php

namespace Database\Factories;

use App\Models\SintaksTahapTujuNilai;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SintaksTahapTujuNilai>
 */
class SintaksTahapTujuNilaiFactory extends Factory
{
    protected $model = SintaksTahapTujuNilai::class;

    public function definition()
    {
        return [
            'sintaks_penilaian_id' => null,
            'user_id' => null,
            'nilai_kriteria' => json_encode([
                'kriteria' => [
                    ['nama' => 'Kreativitas', 'nilai_tertimbang' => 4, 'bobot' => 2],
                    ['nama' => 'Kolaborasi', 'nilai_tertimbang' => 5, 'bobot' => 3]
                ]
            ]),
            'total_nilai_individu' => 85,
        ];
    }
}
