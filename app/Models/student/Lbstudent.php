<?php

namespace App\Models\student;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lbstudent extends Model
{
    protected $fillable = ['Full_name', 'email', 'phone', 'roll_no', 'program', 'session', 'semester', 'password'];
    use HasFactory;
}
