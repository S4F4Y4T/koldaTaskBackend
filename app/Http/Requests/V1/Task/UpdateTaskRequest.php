<?php

namespace App\Http\Requests\V1\Task;

use App\Enums\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'deadline' => ['sometimes', 'date'],
            'assigned_user_id' => ['sometimes', 'exists:users,id'],
            'status' => ['sometimes', Rule::in(TaskStatus::values())],
        ];
    }

    public function messages(): array
    {
        return [
            'title.max' => 'Task title cannot exceed 255 characters.',
            'description.string' => 'Task description must be a string.',
            'deadline.date' => 'Deadline must be a valid date.',
            'assigned_user_id.exists' => 'Selected user does not exist.',
            'status.in' => 'Invalid task status selected.',
        ];
    }
}
