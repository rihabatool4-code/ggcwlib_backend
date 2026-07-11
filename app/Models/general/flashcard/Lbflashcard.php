<?php

namespace App\Models\general\flashcard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lbflashcard extends Model
{
    protected $table = 'lbflashcards';
 
    protected $fillable = ['lbstudent_id', 'lbteacher_id', 'title', 'subtitle', 'type', 'descryption'];
}
