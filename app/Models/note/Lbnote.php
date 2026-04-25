<?php

namespace App\Models\note;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lbnote extends Model
{
    protected $fillable =['title', 'subject', 'pdf_file'];
    use HasFactory;
}
