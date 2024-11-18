<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Official;

class Service extends Model
{
    protected $fillable = [
        'official_id',
        'title',
        'description',
        'eligibility',
        'category',
        'date_start',
        'date_end',
        'status',
    ];


    public function official()
    {
        return $this->belongsTo(Official::class, 'official_id');
    }
}
