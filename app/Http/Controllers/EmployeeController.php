<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Storage;

class EmployeeController extends Controller
{
    public function index()
    {

        return view('admin.manage-employees.index', [
            'employees' => User::all(),
        ]);
    }

    public function edit(User $username)
    {
        if ($username->id == auth()->user()->id) {
            if (request()->routeIs('manage-employee')) {
                return redirect()->route('edit-user');
            } elseif (request()->routeIs('employee-absences')) {
                return redirect()->route('manage-absences');
            }

        }

        if (auth()->user()->role->id >= $username->role->id && ! auth()->user()->isFirstRootAdmin()) {
            $updatedUsers = session('users_updated', []);
            if (! in_array($username->id, $updatedUsers)) {
                return redirect()->route('manage-employees')->with('error', 'You cannot edit this user!');
            }

        }
        $employee = User::where('username', $username->username)->firstOrFail();
        if (request()->routeIs('manage-employee')) {
            return view('admin.manage-employees.profile.edit', ['employee' => $employee]);
        } elseif (request()->routeIs('employee-absences')) {
            return view('admin.manage-employees.absences.edit', ['employee' => $employee]);
        }

    }

    public function destroy(User $username)
    {
        try {
            $user = User::where('username', $username->username)->firstOrFail();
            $currentAvatar = asset($user->avatar);
            $defaultAvatar = asset('default-avatar.png');

            if ($currentAvatar !== $defaultAvatar) {

                Storage::delete('avatars/'.$user->avatar);
            }

            $user->delete();

            return redirect()->route('manage-employees', ['username' => $user->username])->with('success', 'User deleted!');
        } catch (\Error $e) {
            return redirect()->route('manage-employees', ['username' => $user->username])->with('error', 'Failed to delete the User!');
        }

    }

    public function destroyAvatar(User $username)
    {
        try {
            $user = User::where('username', $username->username)->firstOrFail();

            $currentAvatar = asset($user->avatar);
            $defaultAvatar = asset('default-avatar.png');

            if ($currentAvatar !== $defaultAvatar) {
                if (Storage::exists('avatars/'.$user->avatar)) {

                    Storage::delete('avatars/'.$user->avatar);

                    $user->update([
                        'avatar' => 'default-avatar.png',
                    ]);

                    return back()->with('success', 'Avatar Deleted!');
                } else {
                    return back()->with('error', 'Avatar Not Found!');
                }

            } else {
                return back()->with('info', 'This Employee has no avatar set!');
            }
        } catch (\Error $e) {
            return redirect()->route('manage-employees', ['username' => $user->username])->with('error', 'Failed to delete the Avatar!');
        }

    }

    public function update(UpdateUserRequest $request)
    {

        $attributes = $request->validated();
        $user = User::where('id', $request->get('userid'))->firstOrFail();

        if ($request->has('avatar')) {
            $currentAvatar = asset($user->avatar);
            $defaultAvatar = asset('default-avatar.png');

            if ($currentAvatar !== $defaultAvatar) {
                if (Storage::exists($user->avatar)) {
                    Storage::delete($user->avatar);
                }

            }
            $attributes['avatar'] = $request->file('avatar')->store('avatars');

            $attributes['avatar'] = substr($attributes['avatar'], 8);
        }

        if ($request->has('total_absence_days')) {
            $attributes['total_absence_days'] = $request->get('total_absence_days');

        }

        if ($request->has('remaining_absence_days')) {
            $attributes['remaining_absence_days'] = $request->get('remaining_absence_days');
        }

        if ($request->filled('new-password') || $request->filled('confirm-new-password')) {

            $request->validate([
                'new-password' => 'required|min:7|max:255',
                'confirm-new-password' => 'required|min:7|max:255|same:new-password',
            ]);

            $attributes['password'] = bcrypt($request->get('new-password'));
        }

        if ($request->has('isBlocked')) {
            $attributes['isBlocked'] = true;
        } else {
            $attributes['isBlocked'] = false;
        }

        $attributes['role_id'] = $request->get('role');

        $updatedUsers = session('users_updated', []);
        if (! in_array($user->id, $updatedUsers) && auth()->user()->role->id >= $user->role->id && ! auth()->user()->isFirstRootAdmin()) {
            return redirect()->route('manage-employees')->with('error', 'You cannot edit this user!');
        } else {
            $user->update($attributes);

        }

        $updatedUsers = session('users_updated', []);
        $updatedUsers[] = $user->id;
        session(['users_updated' => $updatedUsers]);

        return redirect()->route('manage-employee', ['username' => $user->username])->with('success', 'Profile Updated!');
    }

    public function store(StoreEmployeeRequest $request)
    {
        $attributes = $request->validated();

        if ($request->has('avatar')) {
            $attributes['avatar'] = $request->file('avatar')->store('avatars');

            $attributes['avatar'] = substr($attributes['avatar'], 8);

        }

        if ($attributes['role'] == \App\Models\Role::where('name', \App\Models\Role::ADMIN)->first()->id) {
            $attributes['role_id'] = 2;
        } elseif ($attributes['role'] == \App\Models\Role::where('name', \App\Models\Role::ROOT_ADMIN)->first()->id) {
            $attributes['role_id'] = 1;
        } else {
            $attributes['role_id'] = 3;
        }

        $attributes['isBlocked'] = $request->has('isBlocked');
        $attributes['password'] = bcrypt($attributes['password']);

        User::create($attributes);

        return redirect('/')->with('success', 'Employee Created!');
    }

    public function create()
    {
        return view('admin.manage-employees.create', [
            'roles' => \App\Models\Role::all(),
        ]);
    }
}
