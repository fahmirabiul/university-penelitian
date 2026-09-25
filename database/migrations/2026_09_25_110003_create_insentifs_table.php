<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('insentifs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('publikasi_id')->constrained('publikasis')->cascadeOnDelete();
            $table->foreignId('periode_insentif_id')->constrained('periode_insentifs')->cascadeOnDelete();
            $table->string('status')->default('diajukan');
            $table->bigInteger('total_dana')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insentifs');
    }
};
