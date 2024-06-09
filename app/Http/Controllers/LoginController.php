<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;




class LoginController extends Controller
{
    public function login(Request $request){

        /*$request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);*/

       $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return jsonResponse(status: 401, message: 'Unauthorized');
        }

        return jsonResponse(data: [
            'token' => $token,
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
