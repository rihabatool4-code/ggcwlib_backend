<?php

namespace App\Models\admin\bookConfig;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LbBookconfig extends Model
{
    use HasFactory;

    protected $table = "book_configurations";

    protected $fillable = [

        "fine_per_day",

        "max_issue_days",

        "max_books_student",

        "max_books_staff",

        "lost_book_fine",

        "damaged_book_fine"

    ];
}