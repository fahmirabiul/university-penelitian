<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penelitians', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('abstrak');
            $table->string('status_saat_ini')->default('draft');
            $table->date('tanggal_pengajuan')->nullable();
            
            // Additional Recommended Columns
            $table->string('skema_penelitian')->nullable();
            $table->bigInteger('total_dana_diajukan')->nullable();
            $table->string('tahun_akademik')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penelitians');
    }
};
