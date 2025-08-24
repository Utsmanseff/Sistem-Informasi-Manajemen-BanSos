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
        Schema::create('jenis_bantuan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bantuan', 100)->unique(); 
            $table->text('deskripsi')->nullable();
            $table->decimal('nominal_dasar', 15, 2)->nullable(); 
            $table->decimal('nominal_tambahan_anak', 15, 2)->nullable();
            $table->integer('maksimal_anak_tambahan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_bantuan');
    }
};
