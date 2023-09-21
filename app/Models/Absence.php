<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absence extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'absence_reason_id',
        'comment',
        'document',
        'original_document_name',
        'approved_by',
        'approved_at',
        'status_id',
    ];

    protected $with = [
        'reason',
        'status',
    ];

    public function setStatusIdAttribute($value)
    {
        if (auth()->user()?->isAdmin()) {
            $this->attributes['status_id'] = $value;
            if ($value != 1) {
                $this->attributes['approved_by'] = auth()->user()->id;
                $this->attributes['approved_at'] = now();
            }
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reason()
    {
        return $this->belongsTo(AbsenceReason::class, 'absence_reason_id');
    }

    public function status()
    {
        return $this->belongsTo(AbsenceStatus::class);
    }

    public function getReasonName()
    {
        return $this->reason->reason;
    }

    public function getStatusName()
    {
        return $this->status->status;
    }
}
