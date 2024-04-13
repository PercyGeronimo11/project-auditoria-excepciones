<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TablaExcepcion extends Model
{
    use HasFactory;
    
    protected $table = 'tabla_excepciones';

    protected $fillable = [
        'id_integridad',
        'data_key',
        'message',
    ];

    // Relación con la tabla de integridad
    public function integridad()
    {
        return $this->belongsTo(TablaIntegridad::class, 'id_integridad');
    }
}
