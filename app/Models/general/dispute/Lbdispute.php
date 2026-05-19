<?php


namespace App\Models\general\dispute;

// namespace App\Models\dispute;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lbdispute extends Model
{
    protected $fillable =['lbteacher_id','lbstudent_id','subject', 'lbbook_id', 'category','description', 'status'];
    use HasFactory;
}
