<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\contracts\JWTSubject;

class Lbadmin extends Authenticatable implements JWTSubject
{
    protected $fillable = ['name', 'email', 'password', 'inApp_notif', 'email_notif'];
    
    use HasFactory;

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return ["guard" => "admin"];
    }
}
