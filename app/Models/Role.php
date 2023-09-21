<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    const ROOT_ADMIN = 'rootadmin';

    const ADMIN = 'admin';

    const STAFF = 'staff';

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
