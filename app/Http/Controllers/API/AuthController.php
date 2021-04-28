<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name'     => 'required|max:55',
            'email'    => 'email|required|unique:users',
            'password' => 'required|confirmed',
            'role_type'=> 'sometimes|max:55'
        ]);

        $validatedData['password'] = Hash::make( $request->password );

        $user = User::create($validatedData);
        
        $accessToken = '';

        if( $user->role_type == 'admin' ) {
            $accessToken = $user->createToken('authToken')->accessToken;
        }

        return response([ 'user' => $user, 'access_token' => $accessToken ]);
    }

    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if( !auth()->attempt( $loginData ) ) {
            return response([ 'message' => 'User does not exist.', 'status' => 400 ]);
        }

        $accessToken = '';

        if( auth()->user()->role_type == 'admin' ) {
            $accessToken = auth()->user()->createToken('authToken')->accessToken;
        }

        return response([ 'user' => auth()->user(), 'access_token' => $accessToken ]);
    }
}
