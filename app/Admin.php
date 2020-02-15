<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    
    protected $guard = 'admins';
    protected $fillable = [ 
        'name', 'email', 'password',
    ];

    protected  $hidden = [
        'password', 'remember_token',
        ];
}
