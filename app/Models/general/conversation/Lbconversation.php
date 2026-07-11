<?php

namespace App\Models\general\conversation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\general\dispute\Lbdispute;
use App\Models\student\Lbstudent;
use App\Models\teacher\Lbteacher;
use App\Models\general\chat\Lbchat;

class Lbconversation extends Model
{
    use HasFactory;
    protected $fillable = ['lbstudent_id', 'lbteacher_id', 'lbadmin_id', 'lbdispute_id', 'type'];

     public function chats()
    {
        return $this->hasMany(Lbchat::class, 'lbconversation_id');
    }
 
    public function dispute()
    {
        return $this->belongsTo(Lbdispute::class, 'lbdispute_id');
    }
    public function student()
    {
    return $this->belongsTo(Lbstudent::class,'lbstudent_id');
    }

    public function teacher()
    {
    return $this->belongsTo(Lbteacher::class,'lbteacher_id');
    }
}
