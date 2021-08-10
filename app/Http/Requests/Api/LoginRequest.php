<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends AbstractRequest
{

    public function rules()
    {
        return [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ];
    }
}
