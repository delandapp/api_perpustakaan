<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KoleksiPribadi extends Model
{
    use HasFactory;
    public $incrementing = true;
    protected $table = "koleksi_pribadis";
    protected $primaryKey = "KoleksiID";
    protected $keyType = "int";
    protected $guarded = ['KoleksiID'];
}
