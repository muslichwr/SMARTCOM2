<?php

namespace Database\Seeders;

use App\Models\Materi;
use Carbon\Carbon;
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
        $createdAt = Carbon::create(2024, 5, 1, 9, 40, 39);

        // Beberapa topik materi untuk referensi
        $topikMateri = [
            'Konsep Jaringan Dasar',
            'Prinsip Dasar TCP/IP dan Alamat IP',
            'Simulasi Jaringan Dasar',
        ];
        
        // Membuat 15 materi dengan data yang berbeda
        foreach ($topikMateri as $index => $topik) {
            $judul = $topik;
            $slug = Str::slug($judul);
            $pjblActive = $faker->boolean(70); // 70% aktif
        
            // Deskripsi manual berdasarkan topik
            $deskripsiMap = [
                'Konsep Jaringan Dasar' => 'Mempelajari integrasi elemen komunikasi modern berbasis protokol TCP/IP, termasuk peran perkembangan media komunikasi dalam mendukung efisiensi pertukaran data antar perangkat.',
                'Prinsip Dasar TCP/IP dan Alamat IP' => 'Membahas struktur, fungsi, dan mekanisme TCP/IP serta sistem pengalamatan IP untuk merutekan data dalam jaringan, termasuk relevansinya dengan kebutuhan teknologi industri.',
                'Simulasi Jaringan Dasar' => 'Praktik merancang konfigurasi jaringan melalui simulasi topologi, perangkat, dan protokol untuk menganalisis pengaruh desain jaringan terhadap kecepatan dan stabilitas komunikasi.',
            ];
        
            $deskripsi = $deskripsiMap[$topik] ?? $faker->paragraph(rand(3, 5)); // fallback ke faker jika tidak ada
        
            Materi::create([
                'judul' => $judul,
                'slug' => $slug,
                'deskripsi' => $deskripsi,
                'pjbl_sintaks_active' => $pjblActive,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }
    }
} 