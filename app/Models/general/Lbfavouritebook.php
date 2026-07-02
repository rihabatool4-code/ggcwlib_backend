<?php

namespace App\Models\general;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\note\Lbnote;
use App\Models\admin\Lbebook;

class Lbfavouritebook extends Model
{
    protected $fillable = ['lbstudent_id', 'lbteacher_id', 'lbebook_id', 'lbnote_id'];
    use HasFactory;

    public function note()
    {
        return $this->belongsTo(Lbnote::class, 'lbnote_id');
    }

    // Added: relationship to the saved ebook (digital library book)
    public function ebook()
    {
        return $this->belongsTo(Lbebook::class, 'lbebook_id');
    }
}