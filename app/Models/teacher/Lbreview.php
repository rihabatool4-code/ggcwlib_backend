<?php

namespace App\Models\Teacher;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lbreview extends Model
{
    protected $fillable =['lbteacher_id','rating','review'];
    use HasFactory;
}
