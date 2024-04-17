<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sequence_result extends Model
{
    use HasFactory;
    protected $table = 'sequence_results';
    protected $primaryKey = 'id';
    protected $fillable = [
        'bdManager', 
        'dbName', 
        'tableName', 
        'field', 
        'sequenceType',
        'sequenceOrder', 
        'increment',
        'state',
        'user',
        'observation',
        'url_doc',
        'created_at',
        'updated_at'
    ];
}
