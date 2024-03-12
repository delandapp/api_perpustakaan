<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailsUser extends Model
{
    use HasFactory;
    public $incrementing = true;
    protected $table = "detail_users";
    protected $primaryKey = "id";
    protected $keyType = "int";
    protected $guarded = ['id'];
}
