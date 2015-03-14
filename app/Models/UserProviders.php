<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProviders extends Model
{
    protected $fillable = ['user_id', 'provider', 'provider_id'];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
