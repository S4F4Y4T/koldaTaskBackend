<?php

namespace App\DTOs\V1;

use Illuminate\Http\Request;

class ProjectDTO
{
    public function __construct(
        public readonly string $title,
        public readonly string $client,
        public readonly string $start_date,
        public readonly string $end_date,
        public readonly string $status,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            title: $request->validated('title'),
            client: $request->validated('client'),
            start_date: $request->validated('start_date'),
            end_date: $request->validated('end_date'),
            status: $request->validated('status'),
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            client: $data['client'],
            start_date: $data['start_date'],
            end_date: $data['end_date'],
            status: $data['status'],
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'title' => $this->title,
            'client' => $this->client,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
        ], fn($value) => !is_null($value));
    }
}
