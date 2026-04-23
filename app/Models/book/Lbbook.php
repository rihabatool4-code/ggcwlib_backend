<?php

namespace App\Models\book;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lbbook extends Model
{
    protected $fillable = ['title', 'author', 'accession_no', 'dept', 'total_copies', 'img', 'description', 'is_donated'];
    use HasFactory;
}
