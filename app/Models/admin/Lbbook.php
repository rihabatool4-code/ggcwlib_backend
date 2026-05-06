<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lbbook extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'author', 'accession_no', 'dept', 'total_copies', 'img', 'is_donated'];
}
