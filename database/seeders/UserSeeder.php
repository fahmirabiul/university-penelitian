<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');

        // 1. Super Admin
        User::create([
            'sso_id' => '11111111-1111-1111-1111-100000000001',
            'name' => 'Administrator Utama',
            'email' => 'admin@university.ac.id',
            'role_lokal' => 'admin_lembaga',
        ]);

        // 2. Admin Unit (4 Users)
        for ($i = 1; $i <= 4; $i++) {
            User::create([
                'sso_id' => '11111111-1111-1111-1111-1000000000' . sprintf('%02d', 1 + $i),
                'name' => "Admin Unit $i",
                'email' => "admin.unit{$i}@university.ac.id",
                'role_lokal' => 'admin_lembaga',
            ]);
        }

        // 3. Dosen (10 Users)
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'sso_id' => '11111111-1111-1111-1111-1000000000' . sprintf('%02d', 5 + $i),
                'name' => $faker->name,
                'email' => "dosen{$i}@university.ac.id",
                'role_lokal' => 'dosen',
            ]);
        }

        // 4. Mahasiswa (5 Users)
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'sso_id' => '11111111-1111-1111-1111-1000000000' . sprintf('%02d', 15 + $i),
                'name' => $faker->name,
                'email' => "mhs{$i}@student.ac.id",
                'role_lokal' => 'mahasiswa',
            ]);
        }
    }
}
