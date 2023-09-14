<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
//use Illuminate\Http\Request;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Requests\User\LoginRequest;
use App\Services\User\UserService;

class UserController extends Controller
{
    public function register(RegisterRequest $request, UserService $userService)
    {
        try{
            $user = $userService->store($request);
           // $token = $user->createToken('user_token')->plainTextToken;

            return response()->json(['user' => $user], 200);
        }catch(\Exception $e){
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in UserController.register.'
            ]);
        }
    }

    public function login(LoginRequest $request, UserService $userService)
    {
        try{

            return $userService->authenticateUser($request);

        }catch(\Exception $e){
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in UserController.login.'
            ]);
        }
    }

    public function logout(UserService $userService)
    {
        try {

            return $userService->logout();

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in AuthController.logout'
            ]);
        }
    }
}
