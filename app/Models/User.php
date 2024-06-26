<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    
    public $incrementing = true;
    protected $table = "users";
    protected $primaryKey = "id";
    protected $keyType = "int";
    protected $guarded = ['id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'Username',
        'Email',
        'Password',
        'NamaLengkap',
        'Level',
        'NoTelepon',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relations
    public function peminjaman(): HasMany {
        return $this->hasMany(Peminjaman::class, 'UserID', 'id');
    }

    public function ulasan(): HasMany {
        return $this->hasMany(Peminjaman::class, 'UserID', 'id');
    }

    public function koleksipribadi(): BelongsToMany
    {
            return $this->belongsToMany(Buku::class, 'koleksi_pribadis', 'UserID', 'BukuID');
    }

    public function detailsuser(): HasMany
    {
            return $this->hasMany(DetailsUser::class, 'UserID', 'id',);
    }
}
