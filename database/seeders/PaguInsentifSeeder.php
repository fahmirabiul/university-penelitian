<?php

namespace Database\Seeders;

use App\Models\PaguInsentif;
use Illuminate\Database\Seeder;

class PaguInsentifSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['tingkat_quartil' => 'Q1', 'nominal_base' => 15000000],
            ['tingkat_quartil' => 'Q2', 'nominal_base' => 12000000],
            ['tingkat_quartil' => 'Q3', 'nominal_base' => 10000000],
            ['tingkat_quartil' => 'Q4', 'nominal_base' => 8000000],
            ['tingkat_quartil' => 'SINTA 1', 'nominal_base' => 7000000],
            ['tingkat_quartil' => 'SINTA 2', 'nominal_base' => 5000000],
        ];

        foreach ($data as $item) {
            PaguInsentif::create($item);
        }
    }
}
