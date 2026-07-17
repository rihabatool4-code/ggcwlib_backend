<?php

namespace App\Models\general\reviews;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\student\Lbstudent;
use App\Models\teacher\Lbteacher;

class LbReview extends Model
{
    use HasFactory;
    protected $table = "lbreviews";
    protected $fillable = ['lbteacher_id','lbstudent_id','rating','review','status'];

    public function lbstudent()
    {
        return $this->belongsTo(Lbstudent::class, 'lbstudent_id');
    }

    public function lbteacher()
    {
        return $this->belongsTo(Lbteacher::class, 'lbteacher_id');
    }
}