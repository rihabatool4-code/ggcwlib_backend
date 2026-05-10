<?php

namespace App\Models\dispute;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lbdispute extends Model
{
    protected $fillable =['subject', 'relatedbooks', 'category','description'];
    use HasFactory;
}
