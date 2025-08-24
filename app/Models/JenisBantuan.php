<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisBantuan extends Model
{
    use HasFactory;

    protected $table = 'jenis_bantuan';

    protected $fillable = [
        'nama_bantuan',
        'deskripsi',
        'nominal_dasar',
        'nominal_tambahan_anak',
        'maksimal_anak_tambahan',
    ];

    protected $casts = [
        'nominal_dasar' => 'decimal:2',
        'nominal_tambahan_anak' => 'decimal:2',
        'maksimal_anak_tambahan' => 'integer',
    ];

    public function penerimaBantuan(): HasMany
    {
        return $this->hasMany(PenerimaBantuan::class);
    }
}