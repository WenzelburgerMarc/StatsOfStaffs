<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'avatar',
        'role_id',
        'isBlocked',
        'total_absence_days',
        'remaining_absence_days',
    ];

    protected $with = ['absences', 'sentChats', 'receivedChats', 'role'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function setRoleIDAttribute($value)
    {

        if (! $this->exists || (($value == 1 || $value == 2 || $value == null) && auth()->user()->isRootAdmin())) {
            $this->attributes['role_id'] = $value ?? User::find($this->id)->role_id;
        } elseif (($value == 3 || $value == null || $value == 2) && (auth()->user()->isAdmin() || auth()->user()->isRootAdmin())) {
            $this->attributes['role_id'] = $value ?? User::find($this->id)->role_id;
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function isRootAdmin(): bool
    {
        return $this->role->name == Role::ROOT_ADMIN;
    }

    public function isAdmin(): bool
    {
        return $this->role->name == Role::ADMIN || $this->isRootAdmin() || $this->isFirstRootAdmin();
    }

    public function isFirstRootAdmin()
    {
        $user = User::where('role_id', 1)->first();

        return $user == $this;
    }

    public function isStaff(): bool
    {
        return $this->role->name == Role::STAFF;
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function setIsBlockedAttribute($value)
    {
        if (auth()->check() && auth()->user()->isAdmin()) {
            $this->attributes['isBlocked'] = $value;
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function sentChats()
    {
        return $this->hasMany(Chat::class, 'sender_id');
    }

    public function receivedChats()
    {
        return $this->hasMany(Chat::class, 'receiver_id');
    }

    public function absences()
    {
        return $this->hasMany(Absence::class);
    }

    public function getRouteKeyName()
    {
        return 'username';
    }
}
