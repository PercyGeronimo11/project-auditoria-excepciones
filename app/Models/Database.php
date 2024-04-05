<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Database extends Model
{
    use HasFactory;

    protected $table = 'Database';

    protected $fillable = [
        'tipo', 'host', 'nombre_db', 'usuario', 'contraseña','estado',
    ];

}


