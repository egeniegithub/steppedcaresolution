<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\Models\User;
use Mail;
use Hash;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function showForgetPasswordForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function submitForgetPasswordForm(Request $request)
    {

        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $token = Str::random(64);

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        $user = User::where("email", $request->email)->first();
        if (!empty($user)) {
            $data['email'] = $user->email;
            $data['url'] = (route('reset.password.get', $token));
            $data['subject'] = "Reset Your Password";
            $data['msg'] = "Welcome to Stepped Care Solutions";
            $data['username']  = $user->firstname . ' ' . $user->lastname;
            try {
                Mail::send('emails.forgotpassword', $data, function ($message) use ($data) {
                    $message->to($data['email'])->from('ashakoor@egenienext.com', 'Stepped Care Solutions')->subject($data['subject']);
                });
                return back()->with('success', 'We have e-mailed your password reset link!');
            } catch (Exception $e) {
                return back()->with('error', $e->getMessage());
            }
        } else {
            return back()->with('error', ' Email not found!');
        }
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function showResetPasswordForm($token)
    {
        return view('auth.passwords.reset', ['token' => $token]);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function submitResetPasswordForm(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);

        $updatePassword = DB::table('password_resets')
            ->where([
                'token' => $request->token
            ])
            ->first();

        if (!$updatePassword) {
            return back()->withInput()->with('error', 'Invalid token!');
        }

        $user = User::where('email', $updatePassword->email)
            ->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['token' =>  $request->token])->delete();

        return redirect('/login')->with('success', 'Your password has been changed!');
    }
}
