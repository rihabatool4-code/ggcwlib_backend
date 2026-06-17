<?php

namespace App\Models\general\conversation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\general\chat\Lbchat;

class Lbconversation extends Model
{
    use HasFactory;
    protected $fillable = ['lbstudent_id', 'lbteacher_id', 'lbadmin_id', 'lbdispute_id', 'type'];

     public function chats()
    {
        return $this->hasMany(Lbchat::class, 'lbconversation_id');
    }
 
    /**
     * Optional but useful: link back to the dispute this conversation belongs to.
     * Adjust the namespace below to match wherever your actual Lbdispute model lives.
     */
    public function dispute()
    {
        return $this->belongsTo(\App\Models\general\dispute\Lbdispute::class, 'lbdispute_id');
    }
}
