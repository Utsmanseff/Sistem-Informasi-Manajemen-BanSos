<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PenerimaBantuan extends Model
{
    use HasFactory; 

    protected $table = 'penerima_bantuan';

    protected $fillable = [
        'keluarga_id',
        'jenis_bantuan_id',
        'periode_bantuan',
        'nominal_dihitung',
        'status_penerima',
        'ditugaskan_kepada_distributor_id',
        'catatan',
    ];

    protected $casts = [
        'periode_bantuan' => 'date',
        'nominal_dihitung' => 'decimal:2',
    ];

    public function keluarga(): BelongsTo
    {
        return $this->belongsTo(Keluarga::class);
    }

    public function jenisBantuan(): BelongsTo
    {
        return $this->belongsTo(JenisBantuan::class);
    }

    public function penyaluran(): HasOne
    {
        return $this->hasOne(PenyaluranBantuan::class);
    }

    public function ditugaskanKepadaDistributor()
    {
        return $this->belongsTo(User::class, 'ditugaskan_kepada_distributor_id');
    }
}