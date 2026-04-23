<?php

namespace App\Models\teacher;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lbteacher extends Model
{
    protected $fillable = ['full_name','email','password','phone_no'];
    use HasFactory;
}
