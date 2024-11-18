<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Assistant;

class Residence extends Model
{
    //

    protected $fillable = [
        'user_id', 
        'first_name', 
        'last_name', 
        'date_of_birth', 
        'age', 
        'address', 
        'phone_number', 
        'gender', 
        'address'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assistants()
    {
        return $this->hasMany(Assistant::class , 'resident_id');
    }
}
