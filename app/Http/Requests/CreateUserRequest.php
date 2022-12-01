<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    protected $forceJsonResponse = true;
    public function authorize()
    {
        return true;
    }
    
    public function rules()
    {
        return [
            'name'=>'string|required',
            'email'=>'required|email|unique:users,email|max:value:255',
            'birthdate'=>'required|date|before:today',
            'password' => 'required|string|min:8|confirmed|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
        ];
    }
}