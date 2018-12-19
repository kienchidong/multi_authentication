<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminLoginController extends Controller
{
    //
    public function __contruct()
    {
        $this->middleware('guest:admin');
    }

    public function showLoginForm()
    {
        return view('auth.admin-login');
    }

    public function submitLogin(Request $request)
    {
        $this->validate($request, array(
            'email' => 'required|email',
            'password' => 'required|min:6',
        ));

        if (Auth::guard('admin')->attempt(array(
            'email' => $request->email,
            'password' => $request->password
        ), $request->remember)) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->back()->withInput($request->only('email', 'remember'));
    }


    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        if (!Auth::check() && !Auth::guard('admin')->check()) {
            $request->session()->flush();
            $request->session()->regenerate();
        }
        return redirect(route('admin.login'));
    }
}
