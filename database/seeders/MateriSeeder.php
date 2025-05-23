<?php

namespace Database\Seeders;

use App\Models\Materi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory;

class MateriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create('id_ID');
        
        // Beberapa topik materi untuk referensi
        $topikMateri = [
            'Pemrograman Dasar PHP',
            'Algoritma dan Struktur Data',
            'Pengembangan Web dengan Laravel',
            'Database MySQL',
            'Pemrograman Berorientasi Objek',
            'Front-end Development',
            'Mobile App Development',
            'Jaringan Komputer',
            'Keamanan Sistem Informasi',
            'Desain UI/UX',
            'Machine Learning Dasar',
            'Cloud Computing',
            'Pengembangan Game',
            'Internet of Things (IoT)',
            'Big Data Analytics'
        ];
        
        // Membuat 15 materi dengan data yang berbeda
        foreach ($topikMateri as $index => $topik) {
            $judul = $topik;
            $slug = Str::slug($judul);
            $pjblActive = $faker->boolean(70); // 70% kemungkinan pjbl aktif
            
            Materi::create([
                'judul' => $judul,
                'slug' => $slug,
                'deskripsi' => $faker->paragraph(rand(3, 5)),
                'pjbl_sintaks_active' => $pjblActive
            ]);
        }
        
        // Tambahkan 10 materi lain dengan data random
        for ($i = 0; $i < 10; $i++) {
            $judul = $faker->sentence(rand(3, 6));
            $slug = Str::slug($judul);
            
            Materi::create([
                'judul' => $judul,
                'slug' => $slug,
                'deskripsi' => $faker->paragraph(rand(3, 5)),
                'pjbl_sintaks_active' => $faker->boolean(50)
            ]);
        }
    }
} 