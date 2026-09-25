<?php

namespace App\Services;

use App\Models\Insentif;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class IncentiveCalculatorService
{
    public function calculate(Insentif $insentif): void
    {
        $penelitian = $insentif->publikasi->penelitian;
        $dosens = $penelitian->dosen;

        if ($dosens->isEmpty()) {
            throw new InvalidArgumentException("Tidak ada dosen yang terdaftar pada penelitian ini.");
        }

        $ketua = $dosens->where('pivot.peran', 'ketua')->first();
        $anggota = $dosens->where('pivot.peran', 'anggota');
        
        $totalDana = $insentif->total_dana;

        DB::transaction(function () use ($insentif, $ketua, $anggota, $totalDana) {
            $insentif->distribusi()->delete();

            if ($anggota->isEmpty() && $ketua) {
                $insentif->distribusi()->create([
                    'user_id' => $ketua->id,
                    'peran' => 'ketua',
                    'persentase_potongan' => 100,
                    'nominal_final' => $totalDana,
                ]);
                return;
            }

            if ($ketua && $anggota->isNotEmpty()) {
                $nominalKetua = $totalDana * 0.60;
                $insentif->distribusi()->create([
                    'user_id' => $ketua->id,
                    'peran' => 'ketua',
                    'persentase_potongan' => 60,
                    'nominal_final' => (int) $nominalKetua,
                ]);

                $persentasePerAnggota = 40 / $anggota->count();
                $nominalPerAnggota = ($totalDana * 0.40) / $anggota->count();

                foreach ($anggota as $user) {
                    $insentif->distribusi()->create([
                        'user_id' => $user->id,
                        'peran' => 'anggota',
                        'persentase_potongan' => (int) $persentasePerAnggota,
                        'nominal_final' => (int) $nominalPerAnggota,
                    ]);
                }
            }
        });
    }
}
