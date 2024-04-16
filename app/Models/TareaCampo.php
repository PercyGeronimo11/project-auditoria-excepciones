<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TareaCampo extends Model
{
    use HasFactory;

    protected $table = 'TareaCampo';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'campo', 'condicion', 'fecha', 'tabla','tipoValidar','longitud','condicion_text','tipo','null','estado','baseDatos','url_doc'
    ];

    // public function Acta(){
    //     return $this->HasOne(Acta::class,'idActa','idActa');
    // }
}

// $data = $request->validate([
//     'campo' => 'required',
//     'condicion' => 'required',
//     'tabla' => 'required',
//     'tipoValidar' => 'required',
//     'longitud' => 'numeric', 
//     'condicion_text' => '', 
//     'tipo' => '', 
//     'null' => '', 
// ]);