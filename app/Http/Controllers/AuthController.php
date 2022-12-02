<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function CreateUser(CreateUserRequest $request)
    {
        $validated_data = $request->validated();
        $validated_data['password'] = Hash::make($validated_data['password']);
        $validated_data['birthdate'] = Carbon::parse($validated_data['birthdate'])->format('Y-m-d');
        $user = User::create($validated_data);

        $token = $user->createToken('auth_token')->plainTextToken;
        return response([
                    'status' => 'success',
                    'message' => 'User created successfully',
                    'user' => $user,
                     "authorization" => [
                        "token" => $token,
                        "type" => "bearer"
                    ]
                ], Response::HTTP_CREATED);
    }

    public function Login(Request $request)
    {
        $user  = User::where('email', $request['email'])->first();
        if (!$user || !Hash::check($request['password'], $user->password)) {
            return response([
                'status' => 'failed',
                'message' => 'Invalid credentials'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        return response([
            'status' => 'success',
             "authorization" => [
                "token" => $token,
                "type" => "bearer"
            ]
        ], Response::HTTP_OK);
    }

    public function GetAllUsers(Request $request)
    {
        $users = User::all();
        return response([
            'status' => 'success',
            'message' => $users
        ], Response::HTTP_OK);
    }
}