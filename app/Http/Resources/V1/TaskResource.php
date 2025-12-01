<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * API Resource for Task model
 * 
 * Transforms task data for API responses with consistent formatting.
 */
class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'deadline' => $this->deadline->toISOString(),
            'deadline_formatted' => $this->deadline->format('Y-m-d H:i:s'),
            'status' => [
                'value' => $this->status->value,
                'label' => $this->status->label(),
                'color' => $this->status->color(),
            ],
            'is_overdue' => $this->isOverdue(),
            'is_completed' => $this->isCompleted(),
            'project' => ProjectResource::make($this->whenLoaded('project')),
            'assigned_user' => UserResource::make($this->whenLoaded('assignedUser')),
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }
}
