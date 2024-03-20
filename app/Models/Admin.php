<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory;
    protected $table = "admin"; //cek
    protected $primaryKey = "id"; //cek

    protected $fillable = [
        'id','nama','jabatan','nipp','email'
    ];

    protected $hidden = [
        'password',
    ];
}
