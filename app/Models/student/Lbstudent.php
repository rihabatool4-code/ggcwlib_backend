<?php

namespace App\Models\student;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Lbstudent extends Authenticatable implements JWTSubject
{
    protected $fillable = ['fullName', 'email', 'phone', 'roll_no', 'program', 'session', 'password','emailNotifications','inappNotifications'];
    use HasFactory;

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return ["guard" => "student"];
    }

      public function lbbookings(){
        return $this->hasMany(Lbstudent::class);
    }
}
