<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class AdminController extends Controller
{
    public function index(){
        // login page
        return view('admin.login');
    }

    public function authenticate(Request $request)
    {
        // validate email dan password
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // jika sukses login
        if (Auth::guard('admin')->attempt($credentials)) {
            $user = Auth::guard('admin')->user();

            // redirect ke beranda
            $request->session()->regenerate();
            return redirect()->intended('/admin');
        }

        // jika gagal login
        return back()->withErrors([
            'error' => 'Incorrect email or password',
        ]);
    }

    public function logout(Request $request){
        // logout
        Auth::guard('admin')->logout();
        $request->session()->regenerateToken();
        
        // redirect ke login
        return redirect('/admin');
    }
}
