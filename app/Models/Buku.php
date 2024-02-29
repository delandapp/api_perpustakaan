<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Buku extends Model
{
    use HasFactory;
    public $incrementing = true;
    protected $table = "bukus";
    protected $primary_key = "BukuID";
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
}
