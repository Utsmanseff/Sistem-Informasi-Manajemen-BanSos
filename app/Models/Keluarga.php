<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Keluarga extends Model
{
    use HasFactory; 

    protected $table = 'keluarga';

    protected $fillable = [
        'nik_kepala_keluarga',
        'nama_kepala_keluarga',
        'alamat_lengkap',
        'rt',
        'rw',
        'desa_id',
        'nomor_telepon',
        'status_ekonomi',
        'kondisi_rumah',
        'pendapatan_per_bulan',
        'pekerjaan',
        'is_disabilitas_berat',
        'is_lansia',
        'catatan',
        'jalur_slip_gaji',
        'jalur_foto_kk',
        'jk',
    ];

    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }

    public function anggotaKeluarga(): HasMany
    {
        return $this->hasMany(AnggotaKeluarga::class);
    }

    public function surveis(): HasOne
    {
        return $this->hasOne(Survei::class);
    }

    public function penerimaBantuan(): HasMany
    {
        return $this->hasMany(PenerimaBantuan::class);
    }

    public function getJumlahTanggunganAnakAttribute(): int
    {
        $childrenUnder18 = $this->anggotaKeluarga()
                                ->where('hubungan_dengan_kk', 'anak')
                                ->get() 
                                ->filter(function ($anggota) {
                                    return $anggota->tanggal_lahir !== null &&
                                           Carbon::parse($anggota->tanggal_lahir)->age < 18;
                                })
                                ->count();

        return min($childrenUnder18, $this->getMaximalAnakTambahanForBlt());
    }

    private function getMaximalAnakTambahanForBlt(): int
    {
        $bltType = JenisBantuan::where('nama_bantuan', 'BLT')->first();
        return $bltType ? $bltType->maksimal_anak_tambahan : 0;
    }
}