<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function create() {
        return view('login-register.login-page');
    }

    //checking the credentials for the user log in
    public function store(Request $request) {
        if (Auth::attempt(['username' => request('username'), 'password' => request('password')])) {
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'message' => 'The provided credentials do not match our records.',
        ]);
    }

    //logout function
    public function destroy(Request $request) {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->to('/');
    }

    // public function roleStatus() {
    //     $role = User::where('id', auth()->id())->get();

    //     return view('/', ['role' => $role]);
    // }
}
