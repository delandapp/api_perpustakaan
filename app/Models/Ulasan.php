<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ulasan extends Model
{
    use HasFactory;
    public $incrementing = true;
    protected $table = "ulasan_bukus";
    protected $primary_key = "UlasanID";
    protected $keyType = "int";
    protected $guarded = ['UlasanID'];

    public function buku(): BelongsTo {
        return $this->BelongsTo(Buku::class, 'BukuID', 'BukuID');
    }

    public function users(): BelongsTo {
        return $this->BelongsTo(User::class, 'UserID', 'id');
    }
}
