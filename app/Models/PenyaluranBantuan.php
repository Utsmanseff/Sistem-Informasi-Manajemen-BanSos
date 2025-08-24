<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenyaluranBantuan extends Model
{
    use HasFactory; 

    protected $table = 'penyaluran_bantuan';

    protected $fillable = [
        'penerima_bantuan_id',
        'petugas_penyalur_id',
        'tanggal_penyaluran',
        'jalur_foto_penerima',
        'status_setelah_penyaluran',
        'catatan',
    ];

    protected $casts = [
        'tanggal_penyaluran' => 'datetime',
    ];

    public function penerimaBantuan(): BelongsTo
    {
        return $this->belongsTo(PenerimaBantuan::class);
    }

    public function petugasPenyalur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_penyalur_id');
    }
}