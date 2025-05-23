<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin Users (role_as = 1)
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => 'admin' . $i,
                'email' => 'admin' . $i . '@mail.com',
                'password' => bcrypt('admin123'),
                'role_as' => 1,
            ]);
        }

        // Guru Users (role_as = 2)
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => 'guru' . $i,
                'email' => 'guru' . $i . '@mail.com',
                'password' => bcrypt('guru123'),
                'role_as' => 2,
            ]);
        }

        // Siswa Users (role_as = 0)
        for ($i = 1; $i <= 30; $i++) {
            User::create([
                'name' => 'siswa' . $i,
                'email' => 'siswa' . $i . '@mail.com',
                'password' => bcrypt('siswa123'),
                'role_as' => 0,
            ]);
        }

        // Tambahkan beberapa siswa dengan data faker untuk memperkaya database
        $faker = Factory::create('id_ID');
        for ($i = 0; $i < 15; $i++) {
            User::create([
                'name' => $faker->name(),
                'email' => $faker->email(),
                'password' => bcrypt('password'),
                'role_as' => 0,
            ]);
        }
    }
}
