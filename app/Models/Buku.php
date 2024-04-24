<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Scout\Searchable;

class Buku extends Model
{
    use Searchable; 
    use HasFactory;
    public $incrementing = true;
    public $fillable = [
        'Judul',
        'Deskripsi',
        'Penulis',
        'Penerbit',
        'TahunTerbit',
        'JumlahHalaman',
        'CoverBuku',
    ];
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


    // ... atribut lainnya

    // Tentukan kolom yang akan diindeks untuk pencarian
    public function searchableAs()
    {
        return 'bukus_index';
    }
}
