<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Residence;

class Assistant extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'resident_id',
        'assitant_type',
        'description',
        'date_request',
        'location',
        'status',
        'lat',
        'lng'	
    ];


    /**
     * Get the residence that owns the assistant.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function residence()
    {
        return $this->belongsTo(Residence::class , 'resident_id');
    }
}
