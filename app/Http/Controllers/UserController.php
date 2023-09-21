<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use Illuminate\Validation\ValidationException;
use Storage;

class UserController extends Controller
{
    public function edit()
    {
        return view('staff.edit');
    }

    public function destroyAvatar()
    {
        $currentAvatar = asset(auth()->user()->avatar);
        $defaultAvatar = asset('default-avatar.png');

        if ($currentAvatar !== $defaultAvatar) {
            if (Storage::exists('avatars/'.auth()->user()->avatar)) {

                Storage::delete('avatars/'.auth()->user()->avatar);

                auth()->user()->update([
                    'avatar' => 'default-avatar.png',
                ]);

                return back()->with('success', 'Avatar Deleted!');
            } else {
                return back()->with('error', 'Avatar Not Found!');
            }

        } else {
            return back()->with('info', 'You have no avatar set!');
        }

    }

    public function update(UpdateUserRequest $request)
    {

        $passwordCorrect = true;
        $attributes = $request->validated();

        if ($request->has('avatar')) {
            $currentAvatar = asset(auth()->user()->avatar);
            $defaultAvatar = asset('default-avatar.png');

            if ($currentAvatar !== $defaultAvatar) {
                if (Storage::exists(auth()->user()->avatar)) {
                    Storage::delete(auth()->user()->avatar);
                }

            }
            $attributes['avatar'] = $request->file('avatar')->store('avatars');
            $attributes['avatar'] = substr($attributes['avatar'], 8);
        }

        if (! password_verify($request->get('password'), auth()->user()->password)) {
            $passwordCorrect = false;
            throw ValidationException::withMessages([
                'password' => 'Wrong Password',
            ]);
        }

        if ($request->filled('new-password') || $request->filled('confirm-new-password')) {

            $request->validate([
                'new-password' => 'required|min:7|max:255',
                'confirm-new-password' => 'required|min:7|max:255|same:new-password',
            ]);

            $attributes['password'] = bcrypt($request->get('new-password'));
        }

        if ($passwordCorrect) {
            auth()->user()->update($attributes);

            return back()->with('success', 'Profile Updated!');
        } else {
            return back()->with('error', 'Wrong Password!');
        }

    }
}
