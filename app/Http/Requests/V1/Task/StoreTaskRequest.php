<?php

namespace App\Http\Requests\V1\Task;

use App\Enums\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization handled by policy
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'deadline' => ['required', 'date', 'after:now'],
            'assigned_user_id' => ['required', 'exists:users,id'],
            'status' => ['required', Rule::in(TaskStatus::values())],
        ];
    }

    /**
     * Get custom validation error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Task title is required.',
            'title.max' => 'Task title cannot exceed 255 characters.',
            'description.string' => 'Task description must be a string.',
            'deadline.required' => 'Task deadline is required.',
            'deadline.date' => 'Deadline must be a valid date.',
            'deadline.after' => 'Deadline must be in the future.',
            'assigned_user_id.required' => 'Assigned user is required.',
            'assigned_user_id.exists' => 'Selected user does not exist.',
            'status.required' => 'Task status is required.',
            'status.in' => 'Invalid task status selected.',
        ];
    }
}
