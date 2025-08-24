<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penyaluran_bantuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penerima_bantuan_id')->constrained('penerima_bantuan')->onDelete('cascade');
            $table->foreignId('petugas_penyalur_id')->constrained('users');
            $table->dateTime('tanggal_penyaluran');
            $table->string('jalur_foto_penerima', 255)->nullable();
            $table->enum('status_setelah_penyaluran', ['berhasil_disalurkan', 'tidak_ditemukan', 'menolak']);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyaluran_bantuan');
    }
};
