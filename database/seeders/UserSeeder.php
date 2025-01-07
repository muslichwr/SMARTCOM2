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
        // DB::table('users')->insert([
        //     'name' => 'admin',
        //     'email' => 'admin@gmail.com',
        //     'password' => bcrypt('password'),
        //     'role_as' => 1,
        // ]);

        for ($i=0; $i < 8; $i++) {
            $faker = Factory::create('id_ID');

            User::create([
                'name' => $faker->name(),
                'email' => $faker->email(),
                'password' => bcrypt('password'),
                'role_as' => 0,
            ]);

        }
    }
}
