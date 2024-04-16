<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TablaIntegridad extends Model
{
    use HasFactory;
    protected $table = 'tabla_integridad';

    protected $fillable = [
        'table',
        'column_foreignkey',
        'table_refer',
        'column_primarykey',
        'name_bd',
        'type_bd',
        'estado',
        'user',
        'url_pdf'
    ];

}
