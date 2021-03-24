<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use App\Models\User;
use Auth;

class PublicController extends Controller {

    public function saveUser(Request $request) {
        $this->validate($request,[
            'full_name' =>'required',
            'phone' => 'numeric|digits:10|required|unique:users',
            'password' => 'required|min:6'
        ]);
        
        $data = new User;
        $data->full_name = $request->full_name;
        $data->phone = $request->phone;
        $data->password = Hash::make($request->password);
        $data->save();

        if($data) {
            $token  = $data->createToken('user-token')->plainTextToken;
            return $this->getResponse(200, 'success', 'User created', [
                'token' => $token
            ]);
        }
    }

    public function login(Request $request) {
        $this->validate($request, [
            'phone'    => 'required|numeric|exists:users|digits:10',
            'password' => 'required|min:6'
        ],
        [
            'phone.exists' => 'Phone not found.'
        ]);

        $user = User::where('phone', $request->phone)->first();

        if($user) {
            $check_password = Hash::check($request->password, $user->password);
            
            if($check_password && $user->status == 1) {
                $token  = $user->createToken('user-token')->plainTextToken;
                $response = [
                    'user' => $user,
                    'token' => $token
                ];
                return $this->getResponse(200, 'success', 'User loggedin', $response);
            } else {
                return $this->getResponse(400, 'failed', 'Password not match', null);
            }
        }
        return $this->getResponse(400, 'failed', 'Email not found', null);
    }

    public function loginWithSessionBased(Request $request) {

        $this->validate($request, [
            'email'    => 'required|email|exists:users',
            'password' => 'required|min:6'
        ],
        [
            'email.exists' => 'Email not found.'
        ]);

        if (Auth::guard('users')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json('',204);
        }
        return response()->json('Invalid Credentials',403);
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();
    }
}
