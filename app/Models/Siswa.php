<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Siswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kelas_id',
        'nis',
        'nipd',
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_telepon',
        'no_barcode',
        'status',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    /**
     * Generate kode unik absensi (disimpan sebagai NIPD).
     */
    public static function generateBarcode(string $nipd): string
    {
        return 'A' . $nipd . 'A';
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function absensis(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'aktif'       => 'Aktif',
            'tidak_aktif' => 'Tidak Aktif',
            'pindah'      => 'Pindah',
            'lulus'       => 'Lulus',
            default       => '—',
        };
    }
}
