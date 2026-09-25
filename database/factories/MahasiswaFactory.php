<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MahasiswaFactory extends Factory
{
    public function definition(): array
    {
        $fakultas = ['Fakultas Ilmu Komputer', 'Fakultas Teknik', 'Fakultas Ekonomi', 'Fakultas Ilmu Sosial'];
        $prodi = ['Teknik Informatika', 'Sistem Informasi', 'Teknik Sipil', 'Manajemen', 'Ilmu Komunikasi'];

        return [
            'nim' => $this->faker->unique()->numerify('##########'),
            'nama' => $this->faker->name(),
            'program_studi' => $this->faker->randomElement($prodi),
            'fakultas' => $this->faker->randomElement($fakultas),
            'angkatan' => $this->faker->numberBetween(2020, 2024),
        ];
    }
}
