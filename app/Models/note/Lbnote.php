<?php

namespace App\Models\note;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\teacher\Lbteacher;

class Lbnote extends Model
{
    protected $fillable = ['title', 'subject', 'pdf_file', 'Lbteacher_id'];
    use HasFactory;

    public function lbteacher()
    {
        return $this->belongsTo(Lbteacher::class, 'Lbteacher_id');
    }
}