<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnggotaKeluarga extends Model
{
    use HasFactory; 

    protected $table = 'anggota_keluarga';

    protected $fillable = [
        'keluarga_id',
        'nik',
        'nama',
        'tanggal_lahir',
        'jenis_kelamin',
        'hubungan_dengan_kk',
        'tingkat_pendidikan',
        'is_disabilitas_berat',
        'is_lansia',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'is_disabilitas_berat' => 'boolean',
        'is_lansia' => 'boolean',
    ];

    public function keluarga(): BelongsTo
    {
        return $this->belongsTo(Keluarga::class);
    }
}