<?php

namespace App\Http\Requests\V1\Role;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AssignPermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'permissions' => 'nullable|array',
            'permissions.*' => 'required|exists:permissions,name',
        ];
    }
}
