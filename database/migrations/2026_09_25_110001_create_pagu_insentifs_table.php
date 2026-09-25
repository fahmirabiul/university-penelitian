<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagu_insentifs', function (Blueprint $table) {
            $table->id();
            $table->string('tingkat_quartil');
            $table->bigInteger('nominal_base');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagu_insentifs');
    }
};
