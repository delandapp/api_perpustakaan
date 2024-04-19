<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriBukuRelasi extends Model
{
    use HasFactory;
    protected $table = 'kategoribuku_relasis';
    protected $primaryKey = 'KategoriRelasiID';
    protected $guarded = ['KategoriRelasiID'];
    public $timestamps = false;
    
}
