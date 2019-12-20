<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Company extends Authenticatable
{
    protected $table = "companies";
    protected $fillable = [
        'id','name', 'email', 'logo','website','password',
    ];


}
