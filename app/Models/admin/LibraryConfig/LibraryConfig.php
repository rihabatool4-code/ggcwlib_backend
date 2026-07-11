<?php

namespace App\Models\Admin\LibraryConfig;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryConfig extends Model
{
    use HasFactory;
     protected $table = 'library_configs';

    protected $fillable = [
        'library_name',
        'email',
        'phone',
        'working_hours',
        'address',
    ];
}
