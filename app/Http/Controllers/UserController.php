<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(StoreUserRequest $storeUserRequest)
    {
        $user = new User();
        $user->name = $storeUserRequest->name;
        $user->email = $storeUserRequest->email;
        $user->password = $storeUserRequest->password;
        $user->role = $storeUserRequest->role;
        $user->save();
        return $this->sendResponse($user, 'User created');
    }

    public function login(LoginRequest $loginRequest)
    {
        $user = User::where('email', $loginRequest->email)->first();
        if (!Hash::check($loginRequest->password, $user->password)) {
            return $this->sendError([
                'status' => false,
                'message' => "wrong credentials",
            ], 400);
        }

        return $this->sendResponse([
            'toke' => $user->createToken('authToken')->accessToken,
            'user' => $user
        ], 'User created');
    }
}
