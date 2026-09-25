<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penelitian_reviewer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penelitian_id')->constrained('penelitians')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('nilai_desk_eval', 5, 2)->nullable();
            $table->text('komentar_desk_eval')->nullable();
            $table->decimal('nilai_presentasi', 5, 2)->nullable();
            $table->text('komentar_presentasi')->nullable();
            $table->string('status_review')->default('pending');
            $table->timestamp('tanggal_dinilai')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penelitian_reviewer');
    }
};
