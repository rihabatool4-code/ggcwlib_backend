<?php

namespace App\Models\general\reviews;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lbreview extends Model
{
    protected $fillable =['lbteacher_id','lbstudent_id','rating','review','status'];
    use HasFactory;
}
