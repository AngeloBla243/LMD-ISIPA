<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Mail\ForgotPasswordMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login()
    {
        if (!empty(Auth::check())) {
            return $this->redirectByUserType(Auth::user()->user_type);
        }
        return view('auth.login');
    }

    public function AuthLogin(Request $request)
    {
        $remember = !empty($request->remember) ? true : false;
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember)) {
            return $this->redirectByUserType(Auth::user()->user_type);
        } else {
            return redirect()->back()->with('error', 'Please enter correct email and password');
        }
    }

    protected function redirectByUserType($user_type)
    {
        switch ($user_type) {
            case 1:
                return redirect('admin/dashboard');
            case 2:
                return redirect('teacher/dashboard');
            case 3:
                return redirect('student/dashboard');
            case 4:
                return redirect('parent/dashboard');
            case 5:
                return redirect('departement/dashboard'); // À adapter selon vos routes
            case 6:
                return redirect('jury/dashboard'); // À adapter selon vos routes
            case 7:
                return redirect('apparitorat/dashboard'); // À adapter selon vos routes
            default:
                Auth::logout();
                return redirect('/')->with('error', 'User type not recognized.');
        }
    }

    public function forgotpassword()
    {
        return view('auth.forgot');
    }

    public function PostForgotPassword(Request $request)
    {
        $user = User::getEmailSingle($request->email);
        if (!empty($user)) {
            $user->remember_token = Str::random(30);
            $user->save();
            Mail::to($user->email)->send(new ForgotPasswordMail($user));
            return redirect()->back()->with('success', "Please check your email and reset your password");
        } else {
            return redirect()->back()->with('error', "Email not found in the system.");
        }
    }

    public function reset($remember_token)
    {
        $user = User::getTokenSingle($remember_token);
        if (!empty($user)) {
            $data['user'] = $user;
            return view('auth.reset', $data);
        } else {
            abort(404);
        }
    }

    public function PostReset($token, Request $request)
    {
        if ($request->password == $request->cpassword) {
            $user = User::getTokenSingle($token);
            $user->password = Hash::make($request->password);
            $user->remember_token = Str::random(30);
            $user->save();
            return redirect(url(''))->with('success', "Password successfully reset");
        } else {
            return redirect()->back()->with('error', "Password and confirm password do not match");
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect(url(''));
    }
}
