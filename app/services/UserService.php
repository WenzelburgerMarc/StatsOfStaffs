<?php

// app/Services/UserService.php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function getUsers(string $search = null, string $selectedRole = null, bool $findYourself = false)
    {
        $query = User::query();

        if (! $findYourself) {
            $query->where('id', '!=', auth()->id());
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', '%'.$search.'%')
                    ->orWhere('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            });
        }

        if ($selectedRole) {
            $query->where('role_id', $selectedRole);
        }

        return $query->orderBy('name')->paginate(5);
    }

    public function setRoleID(User $user, $value)
    {
        if (! $user->exists || (($value == 1) && auth()->user()->isRootAdmin())) {
            $user->role_id = $value;
        } elseif (($value == 3 || $value == 2) && ($this->isAdmin($user) || auth()->user()->isRootAdmin())) {
            $user->role_id = $value;
        } else {

            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function blockUser(User $user)
    {
        if (auth()->check() && $this->isAdmin(auth()->user())) {
            $user->isBlocked = true;
        } else {

            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function unblockUser(User $user)
    {
        if (auth()->check() && $this->isAdmin(auth()->user())) {
            $user->isBlocked = false;
        } else {

            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function isBlocked(User $user): bool
    {
        return $user->isBlocked;
    }
}
