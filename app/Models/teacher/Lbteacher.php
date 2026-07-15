<?php

namespace App\Models\teacher;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\general\conversation\Lbconversation;

use App\Models\general\bookings\Lbbooking;
use Tymon\JWTAuth\Contracts\JWTSubject;







class Lbteacher extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'phone', 'password', 'status'];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return ["guard" => "teacher"];
    }

    public function lbbookings()
    {
        return $this->hasMany(Lbbooking::class, 'lbteacher_id');
    }

    public function aiConversation()
    {
        return $this->hasMany(Lbconversation::class, 'lbteacher_id')
            ->where('type', 'ai');
    }
}