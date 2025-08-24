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
        Schema::table('penerima_bantuan', function (Blueprint $table) {
            $table->foreignId('ditugaskan_kepada_distributor_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null')
                ->after('status_penerima');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penerima_bantuan', function (Blueprint $table) {
            $table->dropConstrainedForeignId('ditugaskan_kepada_distributor_id');
            $table->dropColumn('ditugaskan_kepada_distributor_id');
        });
    }
};
