<?php

namespace App\Models\general;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\admin\Lbebook;
use App\Models\note\Lbnote;

class Lbfavouritebook extends Model
{
    use HasFactory;

    protected $fillable = [
        'lbstudent_id',
        'lbteacher_id',
        'lbebook_id',
        'lbnote_id'
    ];

    public function ebook()
{
    return $this->belongsTo(Lbebook::class, 'lbebook_id');
}

public function note()
{
    return $this->belongsTo(Lbnote::class, 'lbnote_id');
}
}