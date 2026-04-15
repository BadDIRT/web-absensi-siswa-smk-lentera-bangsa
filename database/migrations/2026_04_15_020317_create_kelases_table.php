<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jurusan_id')->constrained('jurusans')->cascadeOnDelete();
            $table->string('nama'); // contoh: X RPL 1
            $table->unsignedTinyInteger('tingkat'); // 10, 11, 12
            $table->string('tahun_ajaran', 9); // 2024/2025
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelases');
    }
};
