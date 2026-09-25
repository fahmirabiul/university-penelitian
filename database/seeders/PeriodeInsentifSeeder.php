<?php

namespace Database\Seeders;

use App\Models\PeriodeInsentif;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PeriodeInsentifSeeder extends Seeder
{
    public function run(): void
    {
        PeriodeInsentif::create([
            'nama_periode' => 'Gelombang 1 Tahun 2026',
            'tanggal_mulai' => Carbon::create(2026, 1, 1),
            'tanggal_selesai' => Carbon::create(2026, 6, 30),
            'status_aktif' => true,
        ]);

        PeriodeInsentif::create([
            'nama_periode' => 'Gelombang 2 Tahun 2025',
            'tanggal_mulai' => Carbon::create(2025, 7, 1),
            'tanggal_selesai' => Carbon::create(2025, 12, 31),
            'status_aktif' => false,
        ]);
    }
}
