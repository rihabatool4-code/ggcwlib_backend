<?php

namespace App\Models\general\notification;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\student\Lbstudent;
use App\Models\teacher\Lbteacher;
use App\Models\admin\Lbadmin;

class Lbnotification extends Model
{
    protected $fillable =[ 'lbstudent_id', 'lbteacher_id', 'lbadmin_id','title','subtitle','notification_for','status'];
    use HasFactory;


     public function student() {
        return $this->belongsTo(Lbstudent::class, 'lbstudent_id');
    }
    public function teacher() {
        return $this->belongsTo(Lbteacher::class, 'lbteacher_id');
    }
    public function admin() {
        return $this->belongsTo(Lbadmin::class, 'lbadmin_id');
    }
}
