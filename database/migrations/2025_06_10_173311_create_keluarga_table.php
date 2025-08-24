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
        Schema::create('keluarga', function (Blueprint $table) {
            $table->id();
            $table->string('nik_kepala_keluarga', 16)->unique();
            $table->string('nama_kepala_keluarga');
            $table->string('alamat_lengkap');
            $table->string('rt', 10)->nullable();
            $table->string('rw', 10)->nullable();
            $table->foreignId('desa_id')->constrained('desa'); 
            $table->string('nomor_telepon', 20)->nullable();
            $table->enum('status_ekonomi', ['sangat_miskin', 'miskin', 'rentan', 'menengah']);
            $table->enum('kondisi_rumah', ['sangat_buruk', 'buruk', 'sedang', 'baik']);
            $table->decimal('pendapatan_per_bulan', 15, 2)->nullable();
            $table->string('pekerjaan', 100)->nullable();
            $table->boolean('is_disabilitas_berat')->default(false);
            $table->boolean('is_lansia')->default(false);
            $table->string('jalur_slip_gaji', 255)->nullable(); 
            $table->string('jalur_foto_kk', 255)->nullable(); 
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keluarga');
    }
};
