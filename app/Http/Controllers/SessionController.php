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
            'identity' => 'required|max:255',
            'password' => 'required|min:7|max:255',
        ]);

        $rememberMe = $request->input('rememberme') == 'on';

        if (auth()->attempt(['username' => $attributes['identity'], 'password' => $attributes['password']], $rememberMe)) {

            return $this->successfullLogin();
        }
        if (auth()->attempt(['email' => $attributes['identity'], 'password' => $attributes['password']], $rememberMe)) {

            return $this->successfullLogin();
        }

        return throw ValidationException::withMessages([
            'password' => 'Your provided credentials could not be verified.',

        ]);
    }

    public function successfullLogin()
    {
        $userService = new UserService();
        if ($userService->isBlocked(auth()->user())) {

            $this->destroy(true);

            return redirect('/login')->with('error', 'Access Denied! Your account has been blocked.');
        }

        session()->regenerate();

        return redirect('/')->with('info', 'Welcome Back!');
    }

    public function destroy($blocked = false)
    {
        auth()->logout();
        if (!$blocked) {
            return redirect('/login')->with('info', 'Goodbye!');
        }

    }
}
