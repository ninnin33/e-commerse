<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //
    public function register(Request $request){
        $validated = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'errors' => $validated->errors()
                ], 422);
        }

        if($request['password']){
            $request['password'] = Hash::make($request['password']);
        }

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => $request['password']

        ]);

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'status' => true,
            'message' => 'User registered successfully',
            'data' => [
                'user' => $user,
                'token' => $token
            ]
        ]);

    }
    public function login(Request $req){
        try{
            $validated = Validator::make($req->all(),[
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:8',
            ]);

            if($validated->fails()) {
                return response()->json([
                    'errors' => $validated->errors()
                    ], 422);
            }

            $user = User::where('email', $req->email)->first();

            if(!$user || !Hash::check($req->password, $user->password)){
                return response()->json([
                    'status' => false,
                    'message' => 'Resquire Registation'
                ], 422);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'User logged in successfully',
                'data' => [
                    'user' => $user,
                    'token' => $token
                ]
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function logout(Request $req){
        try{
            $req->user()->currentAccessToken()->delete();
            return response()->json([
                'status' => true,
                'message' => 'User logged out successfully'
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
        
    }

}
