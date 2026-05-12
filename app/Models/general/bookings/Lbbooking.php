<?php

namespace App\Models\general\bookings;

use App\Models\admin\Lbbook;
use App\Models\student\Lbstudent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lbbooking extends Model
{
    use HasFactory;
    protected $fillable = ["lbstudent_id","lbteacher_id","lbbook_id","issuedby", "status", "issue_date", "due_date", "fine" ];

    public function lbstudent(){
        return $this->belongsTo(Lbstudent::class);
    }

    public function lbbook(){
        return $this->belongsTo(Lbbook::class);
    }

}
