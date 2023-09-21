<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsenceReason extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'reason',
    ];

    public function absences()
    {
        return $this->hasMany(Absence::class);
    }
}
