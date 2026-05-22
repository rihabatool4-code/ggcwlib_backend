<?php


namespace App\Models\general\dispute;

//namespace App\Models\dispute;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lbdispute extends Model
{
    protected $fillable =[ 'lbstudent_id', 'lbteacher_id', 'lbbook_id','raisedby', 'subject', 'category','description', 'status'];
    use HasFactory;
}
