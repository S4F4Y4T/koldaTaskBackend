<?php

namespace App\Http\Requests\V1\Project;

use App\Enums\ProjectStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'client' => ['sometimes', 'string', 'max:255'],
            'start_date' => ['sometimes', 'date'],
            'end_date' => ['sometimes', 'date', 'after:start_date'],
            'status' => ['sometimes', Rule::in(ProjectStatus::values())],
        ];
    }

    public function messages(): array
    {
        return [
            'title.max' => 'Project title cannot exceed 255 characters.',
            'client.max' => 'Client name cannot exceed 255 characters.',
            'start_date.date' => 'Start date must be a valid date.',
            'end_date.date' => 'End date must be a valid date.',
            'end_date.after' => 'End date must be after the start date.',
            'status.in' => 'Invalid project status selected.',
        ];
    }
}
