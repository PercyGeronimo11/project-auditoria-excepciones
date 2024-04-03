<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sequence_exception extends Model
{
    use HasFactory;
    protected $table = 'sequence_exeptions';
    protected $primaryKey = 'id';
    protected $fillable = [
        'sequence_result_id',
        'message', 
        'location'
    ];

    public function sequence_result(){
        return $this->BelongsTo(Sequence_result::class,'sequence_result_id');
    }
}
