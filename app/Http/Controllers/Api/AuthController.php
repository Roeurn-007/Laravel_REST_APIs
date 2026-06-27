<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponse;

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        $token = $user->createToken('API Token')->plainTextToken;

        return $this->successResponse('Register successfully', [
            'token' => $token,
            'user' => new UserResource($user),
        ], [], 201);
    }

    public function profile(Request $request)
    {
        return $this->successResponse('Profile retrieved successfully', new UserResource($request->user()));
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || ! Hash::check($request->password, $user->password)) {
            return $this->errorResponse('Invalid email or password.', [], 401);
        }

        $user->tokens()->where('name', 'API Token')->delete();
        $token = $user->createToken('API Token')->plainTextToken;

        return $this->successResponse('Login successfully', [
            'token' => $token,
            'user' => new UserResource($user),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse('Logout successfully');
    }
}
