<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function create()
    {
        return view('sessions.create');
    }

    public function store(Request $request)
    {

        $attributes = $request->validate([
            'username' => 'required|exists:users,username|max:255',
            'password' => 'required|min:7|max:255',
        ]);

        $rememberMe = $request->input('rememberme') == 'on';

        if (auth()->attempt($attributes, $rememberMe)) {

            $userService = new UserService();
            if ($userService->isBlocked(auth()->user())) {

                $this->destroy(true);

                return redirect('/login')->with('error', 'Access Denied! Your account has been blocked.');
            }

            session()->regenerate();

            return redirect('/')->with('info', 'Welcome Back!');
        }

        throw ValidationException::withMessages([
            'password' => 'Your provided credentials could not be verified.',

        ]);
    }

    public function destroy($blocked = false)
    {
        auth()->logout();
        if (!$blocked) {
            return redirect('/login')->with('info', 'Goodbye!');
        }

    }
}
