<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absensi extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = [
        'siswa_id',
        'scanned_by',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'status',
        'keterangan',
        'foto_surat', // Tambahkan ini
    ];

    protected $casts = [
        'tanggal' => 'date',
        // jam_masuk & jam_pulang tetap string, tidak perlu cast
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function scanner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'scanned_by');
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'hadir' => 'Hadir',
            'izin'  => 'Izin',
            'sakit' => 'Sakit',
            'alpa'  => 'Alpa',
            default => '—',
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'hadir' => 'green',
            'izin'  => 'blue',
            'sakit' => 'amber',
            'alpa'  => 'red',
            default => 'gray',
        };
    }
}
