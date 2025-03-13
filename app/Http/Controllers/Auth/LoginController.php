<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle login logic
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Attempt login
        if (Auth::attempt($credentials)) {
            // Success login
            return redirect()->intended($this->redirectTo());
        }

        // If fail, back to login
        return back()->withErrors([
            'email' => 'Email or password incorrect.',
        ]);
    }

    // Logout user
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }

    // Redirect destination after login
    protected function redirectTo()
    {
        return '/home'; // Route ke home page user
    }
}
