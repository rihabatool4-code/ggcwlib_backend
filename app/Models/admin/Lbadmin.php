<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lbadmin extends Model
{
    protected $fillable = ['name', 'email', 'password', 'inApp_notif', 'email_notif'];
    
    use HasFactory;
}
