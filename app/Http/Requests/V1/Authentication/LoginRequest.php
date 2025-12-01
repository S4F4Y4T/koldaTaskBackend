<?php

namespace App\Http\Requests\V1\Authentication;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.exists' => 'No account found with this email address.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
        ];
    }
}
