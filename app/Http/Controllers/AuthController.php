<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;

class AuthController extends Controller
{
    use ApiResponser;
    public function createUser(CreateUserRequest $request)
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

    public function login(Request $request)
    {
        $user  = User::where('email', $request['email'])->first();
        if (!$user || !Hash::check($request['password'], $user->password)) {
            return $this->errorResponse('Invalid credentials', Response::HTTP_UNAUTHORIZED);
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
    
    public function GetAllUsers()
    {
        $users = User::orderBy('id', 'asc')->get();
        
        return $this->createResponse('Users ', $users, Response::HTTP_OK);
    }
}