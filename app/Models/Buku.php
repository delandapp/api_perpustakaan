<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Buku extends Model
{
    use HasFactory;
    public $incrementing = true;
    protected $table = "bukus";
    protected $primaryKey = "BukuID";
    protected $keyType = "int";
    protected $guarded = ['BukuID'];

    public function peminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'BukuID', 'BukuID');
    }

    public function ulasan(): HasMany
    {
        return $this->hasMany(Ulasan::class, 'BukuID', 'BukuID');
    }

    public function kategori(): BelongsToMany
    {
            return $this->belongsToMany(KategoriBuku::class, 'kategoribuku_relasis', 'BukuID', 'KategoriID');
    }

    public function koleksipribadi(): BelongsToMany
    {
            return $this->belongsToMany(User::class, 'koleksi_pribadis', 'BukuID', 'UserID');
    }
}
