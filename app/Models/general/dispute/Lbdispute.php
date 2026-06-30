<?php


namespace App\Models\general\dispute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\student\Lbstudent;
use App\Models\teacher\Lbteacher;

class Lbdispute extends Model
{
    protected $fillable =[ 'lbstudent_id', 'lbteacher_id', 'lbbook_id','raisedby', 'subject', 'category','description', 'status'];
    use HasFactory;


    public function lbteacher()
     {
        return $this->belongsTo(Lbteacher::class, 'lbteacher_id');
      }

    public function lbstudent()
    {
      return $this->belongsTo(Lbstudent::class, 'lbstudent_id');
       }
}
