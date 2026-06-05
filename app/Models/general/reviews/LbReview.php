<?php

namespace App\Models\general\reviews;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LbReview extends Model
{
    use HasFactory;
    protected $table = "lbreviews";
    protected $fillable =['lbteacher_id','lbstudent_id','rating','review','status'];
    
}
