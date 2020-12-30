<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    //
    /**
     * login function
     */
    public function login(Request $request) 
    {
        try{
            $request->validate([
                'email' => 'email|required',
                'password' => 'required'
            ]);

            $credentials = request(['email', 'password']);

            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'status_code' => 500,
                    'message' => 'Unauthorized'
                ]);
            }

            $user = User::where('email', $request->email)->first();
            if (! Hash::check($request->password, $user->password, [])) {
                throw new \Exception('Error in login!');
            }

            $tokenResult = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'status_code' => 200,
                'access_token' => $tokenResult,
                'token_type' => 'Bearer'
            ]);
        } catch (Exception $error) {
            return response()->json([
                'status_code' => 500,      
                'message' => 'Error in Login',
                'error' => $error,
            ]);
        }
    }

    /**
     * register function
     */
    public function register(Request $request)
    {
        try {
            $validator = $this->validateUser();

            if($validator->fails()) {
                return $this->errorResponse($validator->messages(), 422);
            }
    
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => $request->role
            ]);
    
            return $this->successResponse($user, 'User Successfully created', 201);

        } catch(Exception $error) {
            return response()->json([
                'message' => 'error occured',
                'data' => $error
            ]);
        }
    }

    /**
     * is logged in
     */
    public function isLoggin()
    {
        //get user id
        $user_id = auth()->user()->id;

        return response()->json([
            'data' => $user_id
        ]);
    }

    /**
     * log out
     */
    public function logout()
    {
            // Get user who requested the logout
        $user = auth()->user(); //or Auth::user()
            // Revoke current user token
        $revoke = $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();

        if($revoke) {
            return response()->json([
                'message' => 'Logout success'
            ]);
        } else {
            return response()->json([
                'message' => 'Error, cant logout'
            ]);
        }
    }

    /**
     * user validation function`
     */
    /**
     * requuest validator untuk setiap form
     */
    public function validateUser(){
        return Validator::make(request()->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required'
        ]);
    }
}
