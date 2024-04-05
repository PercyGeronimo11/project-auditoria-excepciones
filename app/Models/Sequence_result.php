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
        'tableName', 
        'field', 
        'sequenceType', 
        'increment',
        'result',
        'state',
        'user',
        'observation'
    ];
}
