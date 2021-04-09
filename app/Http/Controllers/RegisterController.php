<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

class RegisterController extends Controller {
    public function create() {
        return view('login-register.register-page');
    }

    //validating and storing the data of the user who wants to register
    public function store() {
        $this->validate(request(), [
            'username' => 'required|unique:users,username',
            'email' => 'required|email',
            'password' => 'required|confirmed',
            // 'image' => 'mimes:jpg,jpeg,bmp,png',
        ]);

        $user = User::create([
            'username' => request('username'),
            'email' => request('email'),
            'password' => request('password'),
            // 'image' => request()->file('users-images')->store('public')
        ]);


        Auth::login($user);

        return redirect()->to('/');
    }
}
