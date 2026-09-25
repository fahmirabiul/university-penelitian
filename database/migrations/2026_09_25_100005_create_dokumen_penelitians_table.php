<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumen_penelitians', function (Blueprint $table) {
            $table->id();
            $table->morphs('documentable'); // documentable_id & documentable_type
            $table->enum('tipe_dokumen', ['proposal', 'laporan_kemajuan', 'laporan_akhir']);
            $table->string('file_path');
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen_penelitians');
    }
};
