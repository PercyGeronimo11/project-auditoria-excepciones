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
        'campo', 'condicion', 'fecha', 'tabla'
    ];

    // public function Acta(){
    //     return $this->HasOne(Acta::class,'idActa','idActa');
    // }
}