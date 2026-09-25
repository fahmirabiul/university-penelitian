<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('publikasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penelitian_id')->constrained('penelitians')->cascadeOnDelete();
            $table->string('judul_publikasi');
            $table->string('tingkat_quartil');
            $table->json('informasi_jurnal')->nullable();
            $table->string('status_publikasi')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publikasis');
    }
};
