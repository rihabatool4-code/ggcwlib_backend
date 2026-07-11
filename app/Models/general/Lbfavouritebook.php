<?php

namespace App\Models\general;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lbfavouritebook extends Model
{
    protected $fillable =[ 'lbstudent_id', 'lbteacher_id', 'lbebook_id','lbnote_id'];
    use HasFactory;
    // app/Models/general/Lbfavouritebook.php mein add karo:

public function note()
{
    return $this->belongsTo(\App\Models\note\Lbnote::class, 'lbnote_id');
}
}
