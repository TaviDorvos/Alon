<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {

    //********************************************************************* LOGIN ***********************************************************************/
    // Creating the view
    public function loginCreate() {
        return view('login-register.login-page');
    }

    //checking the credentials for the user log in
    public function loginStore(Request $request) {
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

    //***************************************************** REGISTER ************************************************************/
    //Creating the view
    public function registerCreate() {
        return view('login-register.register-page');
    }

    //validating and storing the data of the user who wants to register
    public function registerStore() {
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

    //********************************************************************* RESET PASSWORD ***********************************************************************/
    //creating the view
    public function forgot() {
        return view('reset-password.forgot-password');
    }

    public function getPassword($token) {
        $email = request()->email;
        return view('reset-password.reset', ['token' => $token, 'email' => $email]);
    }

    public function password(Request $request) {

        //checking if the the email exists
        $user = User::where('email', '=', $request->email)->first();

        if ($user == null) {
            return redirect()->back()->with(['error' => "Oops. This email doesn't exist."]);
        }

        //inserting the actual email and creating a new random token for it
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => Str::random(40),
            'created_at' => Carbon::now()
        ]);

        //get the token just created above
        $tokenData = DB::table('password_resets')
            ->where('email', $request->email)->first();

        if ($this->sendResetEmail($request->email, $tokenData->token) == null) {
            return redirect()->back()->with(['success' => 'A reset link has been sent to your email address.']);
        } else {
            return redirect()->back()->with(['error' => 'A Network Error occurred. Please try again.']);
        }
    }

    private function sendResetEmail($email, $token) {
        //getting the mail/username from the database
        $user = DB::table('users')->where('email', $email)->select('username', 'email')->first();

        //creating the unique reset link
        $link = URL::to('/') . '/password/reset/' . $token . '?email=' . urlencode($user->email);

        //sending the actual mail
        Mail::send(
            'email.forgot-password-template',
            ['user' => $user, 'link' => $link],
            function ($message) use ($user) {
                $message->to($user->email);
                $message->subject("Reset your password");
            }
        );
    }

    public function resetPassword(Request $request) {
        //validate the fields
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|confirmed',
        ]);

        //validate the token
        $tokenData = DB::table('password_resets')
            ->where('token', $request->token)->first();

        //redirect the user back to the password reset request form if the token is invalid
        if (!$tokenData)
            return view('reset-password.forgot-password');

        //updating the new password for the user
        User::where('email',  $request->email)->update(['password' => Hash::make($request->password)]);

        //delete the token
        DB::table('password_resets')->where('email', $request->email)->delete();

        return redirect('/login')->with('message', 'Your password has been changed!');
    }
}
