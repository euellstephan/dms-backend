<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Official;


class Alert extends Model
{
    protected $fillable =[
        'official_id',
        'title',
        'description',
        'category',
    ];


    public function official()
    {
        return $this->belongsTo(Official::class, 'official_id');
    }
}
