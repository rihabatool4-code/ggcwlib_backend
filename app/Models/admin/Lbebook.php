<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lbebook extends Model
{
    protected $fillable = ['title', 'author', 'dept', 'pdf_file'];
    use HasFactory;
}
