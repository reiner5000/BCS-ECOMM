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
    // Validate email and password
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    // Attempt to log in with the first database
    if (Auth::guard('customer')->attempt($credentials)) {
        // Redirect to homepage if login is successful
        $request->session()->regenerate();
        return redirect()->route('homepage');
    }

    // Initialize secondDbUser to null
    $secondDbUser = null;

    // Check the second database for the user
    try {
        $secondDbUser = \DB::connection('mysql_second')
            ->table('users')
            ->where('email', $credentials['email'])
            ->first();
    } catch (\Exception $e) {
        // Handle any connection errors
        return back()->withErrors(['error' => 'Database connection error.']);
    }

    // Check if the user exists in the second database
    if ($secondDbUser && $secondDbUser->password === $credentials['password']) {
        // Check if the user already exists in the customers table
        $customerExists = \DB::connection('mysql')->table('customer')->where('email', $secondDbUser->email)->exists();

        // If user does not exist in customers table, create a new customer
        if (!$customerExists) {
            $newCustomer = new \App\Models\Customer(); // Replace with your actual Customer model
            $newCustomer->name = $secondDbUser->name; // Adjust the fields as needed
            $newCustomer->email = $secondDbUser->email;
            $newCustomer->password = bcrypt($credentials['password']); // Store password securely
            $newCustomer->save();
        }

        // Attempt to log in using the customer guard
        if (Auth::guard('customer')->attempt($credentials)) {
            // Redirect to homepage if login is successful
            $request->session()->regenerate();
            return redirect()->route('homepage');
        }
    }

    // If all login attempts fail
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


        $secondDbUser = \DB::connection('mysql_second')
        ->table('users')
        ->where('email', $customer->email)
        ->first();

    // If the user exists in the second database, update the password
        if ($secondDbUser) {
            \DB::connection('mysql_second')
                ->table('users')
                ->where('email', $customer->email)
                ->update(['password' => $request->password]); // No hashing since it's plain text
    }
    // Update last used timestamp for the token
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