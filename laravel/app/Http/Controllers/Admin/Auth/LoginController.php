<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return Redirect::route('admin.dashboard');
        }

        return View::make('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            Auth::user()->update(['last_login_at' => now()]);

            LoginAttempt::create([
                'user_id' => Auth::id(),
                'ip_address' => $request->ip() ?? '127.0.0.1',
                'status' => 'success',
                'user_agent' => substr($request->userAgent() ?? '', 0, 255),
            ]);

            return Redirect::intended(route('admin.dashboard'));
        }

        LoginAttempt::create([
            'ip_address' => $request->ip() ?? '127.0.0.1',
            'status' => 'failed',
            'user_agent' => substr($request->userAgent() ?? '', 0, 255),
        ]);

        return Redirect::back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::route('login');
    }
}
