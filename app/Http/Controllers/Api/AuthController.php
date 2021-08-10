<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends AbstractController
{
    public function register (RegisterRequest $request) {

        $user = User::create($request->only('name','email','password'));
        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        $response = ['access_token' => $token];
        return $this->sendResponse($response,200);
    }

    public function login (LoginRequest $request) {

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $access_token = $user->createToken('MyApp')->accessToken;

            return $this->sendResponse(compact('access_token'), 'User login successfully.');
        } else {
            return $this->sendError('Username or Password is not correct', ['error' => 'Unauthorised'],403);
        }
    }

    public function user(Request $request)
    {
        $user = $request->user();
        return $this->sendResponse(compact('user'), 'User login successfully.');
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }
}
