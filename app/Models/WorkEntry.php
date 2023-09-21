<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkEntry extends Model
{
    protected $fillable = [
        'user_id',
        'start_time',
        'end_time',
        'description',
    ];
}
