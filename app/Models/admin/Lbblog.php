<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lbblog extends Model
{
    use HasFactory;
    protected $table = 'lbblogs';

    protected $fillable = [
        'title',
        'author',
        'category',
        'excerpt',
        'blog_content',
        'img',
        'tags',
    ];

    protected $casts = [
        'tags' => 'array', // ✅ JSON array auto convert
    ];
}