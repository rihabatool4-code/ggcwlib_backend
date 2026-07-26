<?php

namespace App\Models\admin\faq;

use Illuminate\Database\Eloquent\Model;

class Lbfaq extends Model
{
    protected $table = 'faqs';

    protected $fillable = [
        'audience',
        'question',
        'answer',
    ];
}