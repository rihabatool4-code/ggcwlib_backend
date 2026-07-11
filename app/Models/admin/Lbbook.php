<?php

namespace App\Models\admin;

use App\Models\general\bookings\lbbooking;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lbbook extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'author', 'accession_no', 'dept', 'total_copies', 'img', 'is_donated'];

    //  Always include available_copies when this model is serialized to JSON
    // (fetchAllBooks, book detail, etc.) — frontend never has to compute it.
    protected $appends = ['available_copies'];

    //  Fixed: was pointing to Lbbook::class (itself). Now correctly
    // points to the booking model.
    public function lbbookings()
    {
        return $this->hasMany(lbbooking::class);
    }

    // ── Active reservations = status "reserved" (pending pickup) or
    //    "issued" (currently checked out). "returned" and rejected
    //    (deleted) bookings don't hold a copy anymore. ──
    public function getAvailableCopiesAttribute()
    {
        $activeCount = $this->lbbookings()
            ->whereIn('status', ['reserved', 'issued'])
            ->count();

        return max(($this->total_copies ?? 0) - $activeCount, 0);
    }
}