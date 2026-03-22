<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class studentData extends Model
{
    use HasFactory;

    protected $fillable = [
        'fullName',
        'phone',
        'email',
        'age',
        'service',
        'resume'
    ];
}
