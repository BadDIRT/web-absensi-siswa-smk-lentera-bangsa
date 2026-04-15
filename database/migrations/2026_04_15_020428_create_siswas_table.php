<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('kelas_id')->constrained('kelases')->cascadeOnDelete();
            $table->string('nis', 20)->unique();
            $table->string('nipd', 20)->unique()->nullable();
            $table->string('nama');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_telepon', 15)->nullable();
            $table->string('no_barcode', 30)->unique()->nullable(); // format CODABAR: A{NIS}B
            $table->enum('status', ['aktif', 'tidak_aktif', 'pindah', 'lulus'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswas');
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropColumn('nipd');
        });
    }
};
