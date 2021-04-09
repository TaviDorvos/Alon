<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller {
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
