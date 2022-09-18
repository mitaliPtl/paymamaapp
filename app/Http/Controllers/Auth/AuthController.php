<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class AuthController extends Controller
{   
    public function login(){
        return view('auth.login');
    }
    /**
     * Handle an authentication attempt.
     *
     * @return Response
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = Auth::attempt($credentials)) {
                return redirect()->route('login')
                    ->withMessage('Invalid Credentials');
            }
        } catch (Auth $e) {
            return redirect()->route('login')
                    ->withMessage('Something went Wrong');
        }

        // $email = $request["email"];
        // $user = User::where('email', $email)->first();
        return view('home');
    }
}
