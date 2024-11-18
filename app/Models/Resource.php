<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Official;

class Resource extends Model
{
    protected $fillable = [
        'official_id',
        'title',
        'description',
        'type',
        'file_path',
 
    ];


    public function official()
    {
        return $this->belongsTo(Official::class, 'official_id');
    }
}
