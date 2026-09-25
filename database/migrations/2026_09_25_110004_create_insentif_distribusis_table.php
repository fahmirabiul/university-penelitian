<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('insentif_distribusis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('insentif_id')->constrained('insentifs')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('peran');
            $table->integer('persentase_potongan');
            $table->bigInteger('nominal_final');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insentif_distribusis');
    }
};
