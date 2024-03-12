<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class KategoriBuku extends Model
{
    use HasFactory;
    public $incrementing = true;
    protected $table = "kategori_bukus";
    protected $primaryKey = "KategoriID";
    protected $keyType = "int";
    protected $guarded = ['KategoriID'];

    public function buku(): BelongsToMany
    {
            return $this->belongsToMany(Buku::class, 'kategoribuku_relasis', 'KategoriID', 'BukuID');
    }
}
