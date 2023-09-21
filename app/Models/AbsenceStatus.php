<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsenceStatus extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'status',
    ];

    public function absences()
    {
        return $this->hasMany(Absence::class);
    }
}
