<?php

namespace App\Services\User;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Auth;

class UserService
{
    public function prepareRegData($userData)
    {
        $authData['first_name'] = $userData['first_name'];
        $authData['last_name'] = $userData['last_name'];
        $authData['email'] = $userData['email'];
        $authData['password'] = Hash::make($userData['password']);

        return $authData;
    }

    public function store($request)
    {
        $auth = User::create($this->prepareRegData($request));

        return $auth;
    }

    public function authenticateUser($request)
    {
        if(Auth::attempt(['email'=>$request['email'], 'password'=>$request['password']]))
        {
            $token = Auth::user()->createToken('user_token')->plainTextToken;
            return response()->json([ 'user' => Auth::user(), 'token' => $token ], 200);
        }
        else{
            return response()->json([
                'errors' => ['email' => ['Invalid Credentials']]
            ], 401);
        }
    }

    public function logout()
    {
        $user_id = Auth::id();
        $user = $this->fetchUser($user_id);

        $user->tokens()->delete();
        return response()->json('User '.$user_id.') '.Auth::user()->email.' logged out!', 200);
    }

    public function fetchUser(int $id)
    {
        $user = User::findOrFail($id);

        return $user;
    }
}
