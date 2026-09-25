<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'sso_id' => Str::uuid(),
            'name' => 'Prof. Dr. Budi Santoso',
            'email' => 'budi.dosen@university.ac.id',
            'role_lokal' => 'dosen',
        ]);

        User::create([
            'sso_id' => Str::uuid(),
            'name' => 'Dr. Siti Aminah',
            'email' => 'siti.dosen@university.ac.id',
            'role_lokal' => 'dosen',
        ]);

        User::create([
            'sso_id' => Str::uuid(),
            'name' => 'Reviewer LPPM Utama',
            'email' => 'reviewer1@university.ac.id',
            'role_lokal' => 'admin_lembaga',
        ]);
    }
}
