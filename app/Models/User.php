<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Daftar role yang tersedia di sistem.
     */
    public const ROLE_ADMINISTRATOR = 'administrator';
    public const ROLE_SCANNER       = 'scanner';
    public const ROLE_SISWA         = 'siswa';

    /**
     * Daftar role beserta labelnya.
     */
    public static function roleLabels(): array
    {
        return [
            self::ROLE_ADMINISTRATOR => 'Administrator',
            self::ROLE_SCANNER       => 'Scanner',
            self::ROLE_SISWA         => 'Siswa',
        ];
    }

    /**
     * Mendapatkan label role dari user ini.
     */
    public function roleLabel(): string
    {
        return self::roleLabels()[$this->role] ?? 'Tidak Diketahui';
    }

    /**
     * Cek apakah user memiliki role tertentu.
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Override: gunakan kolom 'username' untuk login (bukan email).
     */
    public function username(): string
    {
        return 'username';
    }

    public function siswa(): HasOne
    {
        return $this->hasOne(Siswa::class);
    }



    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'username',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
