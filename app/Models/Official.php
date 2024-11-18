<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

use App\Models\Resource;
use App\Models\Service;

class Official extends Model
{
    protected $fillable = [
        'user_id', 
        'first_name', 
        'last_name', 
        'date_of_birth', 
        'age', 
        'address', 
        'phone_number', 
        'gender', 
        'address',
        'position'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function resources()
    {
        return $this->hasMany(Resource::class , 'official_id');
    }

    public function services()
    {
        return $this->hasMany(Service::class , 'official');
    }

    public function alerts()
    {
        return $this->hasMany(Alert::class , 'official');
    }
}
