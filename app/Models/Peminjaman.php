<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Peminjaman extends Model
{
    use HasFactory;
    use HasFactory;
    public $incrementing = true;
    protected $table = "Peminjamans";
    protected $primary_key = "PeminjamanID";
    protected $keyType = "int";
    protected $guarded = ['PeminjamanID'];

    // Relations
    public function users():BelongsTo{
        return $this->belongsTo(User::class, 'UserID', 'id');
    }

    public function buku():BelongsTo{
        return $this->belongsTo(Buku::class, 'BukuID', 'BukuID');
    }
}
