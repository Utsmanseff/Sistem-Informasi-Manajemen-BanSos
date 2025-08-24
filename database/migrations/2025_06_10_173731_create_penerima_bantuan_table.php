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
        Schema::create('penerima_bantuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('keluarga_id')->constrained('keluarga')->onDelete('cascade');
            $table->foreignId('jenis_bantuan_id')->constrained('jenis_bantuan');
            $table->date('periode_bantuan'); 
            $table->decimal('nominal_dihitung', 15, 2)->nullable(); 
            $table->enum('status_penerima', ['belum_tersalurkan', 'tersalurkan'])->default('belum_tersalurkan');
            $table->text('catatan')->nullable();
            $table->timestamps();
            // unique constraint untuk mencegah 1 KK menerima jenis bantuan yang sama di periode yang sama
            $table->unique(['keluarga_id', 'jenis_bantuan_id', 'periode_bantuan'], 'unique_penerima_per_periode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penerima_bantuan');
    }
};
