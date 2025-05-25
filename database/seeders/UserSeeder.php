<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
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
        $createdAt = Carbon::create(2024, 5, 1, 9, 34, 0);

        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin123'),
            'role_as' => 1,
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ]);

        User::create([
            'name' => 'Guru',
            'email' => 'guru@gmail.com',
            'password' => bcrypt('guru123'),
            'role_as' => 2,
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ]);

        $siswaData = [
            ['name' => 'Adonis Alpha', 'email' => 'adonis.alpha@gmail.com', 'password' => 'Adonis123!'],
            ['name' => 'Afrilia Sofiatul', 'email' => 'afrilia.sofiatul@gmail.com', 'password' => 'Afrilia123!'],
            ['name' => 'Alfian Nurdiansyah', 'email' => 'alfian.nurdiansyah@gmail.com', 'password' => 'Alfian123!'],
            ['name' => 'Awan Rizky', 'email' => 'awan.rizky@gmail.com', 'password' => 'Awan123!'],
            ['name' => 'Beryl Naufal', 'email' => 'beryl.naufal@gmail.com', 'password' => 'Beryl123!'],
            ['name' => 'Chrisna Yoga', 'email' => 'chrisna.yoga@gmail.com', 'password' => 'Chrisna123!'],
            ['name' => 'Dika Fatah', 'email' => 'dika.fatah@gmail.com', 'password' => 'Dika123!'],
            ['name' => 'Elsano Yona', 'email' => 'elsano.yona@gmail.com', 'password' => 'Elsano123!'],
            ['name' => 'Enjel Natasya', 'email' => 'enjel.natasya@gmail.com', 'password' => 'Enjel123!'],
            ['name' => 'Galang Anugerah', 'email' => 'galang.anugerah@gmail.com', 'password' => 'Galang123!'],
            ['name' => 'Hafizh Hisyam', 'email' => 'hafizh.hisyam@gmail.com', 'password' => 'Hafizh123!'],
            ['name' => 'Handsa Tirta', 'email' => 'handsa.tirta@gmail.com', 'password' => 'Handsa123!'],
            ['name' => 'Henky Tri', 'email' => 'henky.tri@gmail.com', 'password' => 'Henky123!'],
            ['name' => 'Ibnu Lambang', 'email' => 'ibnu.lambang@gmail.com', 'password' => 'Ibnu123!'],
            ['name' => 'Jaka Firastama', 'email' => 'jaka.firastama@gmail.com', 'password' => 'Jaka123!'],
            ['name' => 'Jesica Tifania', 'email' => 'jesica.tifania@gmail.com', 'password' => 'Jesica123!'],
            ['name' => 'Laurensius Raditya', 'email' => 'laurensius.raditya@gmail.com', 'password' => 'Laurensius123!'],
            ['name' => 'Marwah Cp', 'email' => 'marwah.cp@gmail.com', 'password' => 'Marwah123!'],
            ['name' => 'Maulana Irman', 'email' => 'maulana.irman@gmail.com', 'password' => 'Maulana123!'],
            ['name' => 'Mikail Tama', 'email' => 'mikail.tama@gmail.com', 'password' => 'Mikail123!'],
            ['name' => 'Moh Bayu', 'email' => 'moh.bayu@gmail.com', 'password' => 'Bayu123!'],
            ['name' => 'Muhamad Frenda', 'email' => 'muhamad.frenda@gmail.com', 'password' => 'Frenda123!'],
            ['name' => 'M Fadyel', 'email' => 'm.fadyel@gmail.com', 'password' => 'Fadyel123!'],
            ['name' => 'M Rijal', 'email' => 'm.rijal@gmail.com', 'password' => 'Rijal123!'],
            ['name' => 'M Yodi', 'email' => 'm.yodi@gmail.com', 'password' => 'Yodi123!'],
            ['name' => 'Naufal Satrio', 'email' => 'naufal.satrio@gmail.com', 'password' => 'Naufal123!'],
            ['name' => 'Rafi Dinno', 'email' => 'rafi.dinno@gmail.com', 'password' => 'Rafi123!'],
            ['name' => 'Reno Bayu', 'email' => 'reno.bayu@gmail.com', 'password' => 'Reno123!'],
            ['name' => 'Rizka Adia', 'email' => 'rizka.adia@gmail.com', 'password' => 'Rizka123!'],
            ['name' => 'Risqi Putra', 'email' => 'risqi.putra@gmail.com', 'password' => 'Risqi123!'],
            ['name' => 'Sastra Ageng', 'email' => 'sastra.ageng@gmail.com', 'password' => 'Sastra123!'],
            ['name' => 'Siti Hartita', 'email' => 'siti.hartita@gmail.com', 'password' => 'Siti123!'],
            ['name' => 'Steven Febryan', 'email' => 'steven.febryan@gmail.com', 'password' => 'Steven123!'],
            ['name' => 'Zacky Rayhandhika', 'email' => 'zacky.rayhandhika@gmail.com', 'password' => 'Zacky123!'],
        ];

        foreach ($siswaData as $data) {
            User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'role_as' => 0,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }


    }
}
