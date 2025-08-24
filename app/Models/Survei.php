<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Survei extends Model
{
    use HasFactory; 

    protected $table = 'survei';

    protected $fillable = [
        'keluarga_id',
        'petugas_survei_id',
        'tanggal_survei',
        'latitude',
        'longitude',
        'jalur_foto',
        'status_verifikasi',
        'alasan_penolakan',
        'diverifikasi_oleh',
        'tanggal_verifikasi',
    ];

    protected $casts = [
        'tanggal_survei' => 'datetime',
        'tanggal_verifikasi' => 'datetime',
    ];

    public function keluarga(): BelongsTo
    {
        return $this->belongsTo(Keluarga::class);
    }

    public function petugasSurvei(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_survei_id');
    }

    public function diverifikasiOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }
}