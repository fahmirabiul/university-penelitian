<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            MahasiswaSeeder::class,
            PaguInsentifSeeder::class,
            PeriodeInsentifSeeder::class,
            PenelitianSeeder::class,
        ]);
    }
}
