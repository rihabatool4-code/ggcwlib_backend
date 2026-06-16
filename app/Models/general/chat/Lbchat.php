<?php

namespace App\Models\general\chat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\general\conversation\Lbconversation;

class Lbchat extends Model
{
    use HasFactory;

    protected $fillable = ['lbconversation_id', 'message', 'type', 'sender'];

     /**
     * Inverse of Lbconversation::chats() — each chat message belongs to one conversation.
     */
    public function conversation()
    {
        return $this->belongsTo(Lbconversation::class, 'lbconversation_id');
    }
}
