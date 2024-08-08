<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Models\Customer;
use App\Models\PersonalAccessToken;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail; 
use Carbon\Carbon;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function authenticateLogin(Request $request)
    {
       // validate email dan password
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // jika sukses login
        if (Auth::guard('customer')->attempt($credentials)) {
            $user = Auth::guard('customer')->user();

            // redirect ke beranda
            $request->session()->regenerate();
            return redirect()->route('homepage');
        }

        // jika gagal login
        return back()->withErrors([
            'error' => 'Email atau password salah',
        ]);
    }


    public function register()
    {
        return view('auth.register');
    }

    public function storeregister(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'string|email|max:255',
            'password' => 'required|string|min:8',
        ]);
        $email = Customer::where('email',$request->email)->first();
        if($email){
            return redirect()->route('register')->with('error', 'Email has been registered');
        }else{
            if($request->password != $request->password_confirmation){
                return redirect()->route('register')->with('error', 'Passwords do not match');
            }else{
                DB::beginTransaction();

                try {
                    // Create the new Pelanggan (customer)
                    $customer = new Customer([
                        'name' => $validatedData['nama'],
                        'email' => $validatedData['email'],
                        'password' => Hash::make($validatedData['password']),
                    ]);
                    
                    // Save the customer (customer)
                    $customer->save();
        
                    DB::commit();
        
                    $credentials = $request->validate([
                        'email' => ['required'],
                        'password' => ['required'],
                    ]);
        
                    // Log in the Pelanggan (customer) using the member guard
                    if(Auth::guard('customer')->attempt($credentials)){
                        $user = Auth::guard('customer')->user();
                        $request->session()->regenerate();
                        return redirect()->route('homepage')->with('success', 'Registration successful!');
                    } else {
                        return redirect()->route('register')->with('error', 'Registration failed: ' . $e->getMessage());
                    }
        
                } catch (\Exception $e) {
                        DB::rollBack();
                        // return redirect()->route('register')->with('error', 'Registration failed: ' . $e->getMessage());
                }
            }
        }
        
    }


    public function authenticateRegister(Request $request)
    {
        // 
    }

    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendForgotPassword(Request $request)
    {
        $email = Customer::where('email',$request->email)->first();
        if($email){
            do {
                $number = '';
                for ($i = 0; $i < 12; $i++) {
                    $number .= mt_rand(0, 9);
                }
                $idToken = $number;
        
                $exists = PersonalAccessToken::where('token', $idToken)->exists();
            } while ($exists); 

            $token = $idToken; 

            $pac = new PersonalAccessToken();
            $pac->tokenable_type = 'reset-password';
            $pac->tokenable_id = '0';
            $pac->name = $request->email;
            $pac->token = $idToken;
            $pac->save();

            Mail::to($request->email)->send(new ResetPasswordMail($request->email));
        }
    }

    public function resetPassword($token = null)
    {
        if($token == null){
            return redirect()->intended('/login');
        }else{
             $pac = PersonalAccessToken::where('token',$token)->whereNull('last_used_at')->first();
            if($pac){
                return view('auth.reset-password',['token'=>$token]);
            }else{
                return redirect()->intended('/login');
            }
        }
    }

    public function saveResetPassword(Request $request)
    {
        $pac = PersonalAccessToken::where('token',$request->token)->whereNull('last_used_at')->first();

        $customer = Customer::where('email',$pac->name)->first();
        $customer->password = Hash::make($request->password);
        $customer->save();

        $pac->last_used_at = Carbon::now();
        $pac->save();

        return response()->json(['success' => true]);
    }

    public function logout(Request $request){
        Auth::guard('customer')->logout();

        $request->session()->regenerateToken();
     
        return redirect('/');
    }
}