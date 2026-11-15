<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = [
            ...$request->only('username', 'password'),
        ];

        if (!Auth::attempt($credentials)) {
            return back()->withErrors(['username' => 'Username atau password salah.'])->onlyInput('username');
        }

        $request->session()->regenerate();

        DB::connection('users_igr')->table('users')->where('id', Auth::id())->update(['last_login_at' => now()]);

        return redirect()->intended(route('items.index'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
