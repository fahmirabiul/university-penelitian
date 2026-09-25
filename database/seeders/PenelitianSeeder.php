<?php

namespace Database\Seeders;

use App\Models\Penelitian;
use App\Models\User;
use App\Models\Mahasiswa;
use Illuminate\Database\Seeder;

class PenelitianSeeder extends Seeder
{
    public function run(): void
    {
        $dosen = User::where('role_lokal', 'dosen')->get();
        if ($dosen->count() < 2) return;

        $mahasiswa = Mahasiswa::inRandomOrder()->limit(3)->get();
        $reviewer = User::where('role_lokal', 'admin_lembaga')->first();

        // 1. Penelitian Draft
        $p1 = Penelitian::create([
            'judul' => 'Pengembangan Sistem AI untuk Diagnosa Medis',
            'abstrak' => 'Penelitian ini bertujuan untuk membangun model AI...',
            'status_saat_ini' => 'draft',
            'skema_penelitian' => 'Penelitian Terapan',
            'total_dana_diajukan' => 50000000,
            'tahun_akademik' => '2025/2026',
        ]);
        $p1->dosen()->attach($dosen[0]->id, ['peran' => 'ketua']);
        $p1->dosen()->attach($dosen[1]->id, ['peran' => 'anggota']);

        // 2. Penelitian Desk Eval
        if($reviewer) {
            $p2 = Penelitian::create([
                'judul' => 'Implementasi IoT pada Pertanian Cerdas',
                'abstrak' => 'IoT dapat meningkatkan hasil panen...',
                'status_saat_ini' => 'desk_eval',
                'skema_penelitian' => 'Penelitian Dasar',
                'total_dana_diajukan' => 35000000,
                'tahun_akademik' => '2025/2026',
                'tanggal_pengajuan' => now()->subDays(5),
            ]);
            $p2->dosen()->attach($dosen[1]->id, ['peran' => 'ketua']);
            
            foreach($mahasiswa as $m) {
                $p2->mahasiswa()->attach($m->id);
            }

            $p2->reviewer()->attach($reviewer->id, [
                'status_review' => 'pending'
            ]);
        }
    }
}
