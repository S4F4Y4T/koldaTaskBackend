<?php

namespace App\Filters\V1;

use Illuminate\Database\Eloquent\Builder;

class ProjectFilter extends QueryFilter
{

    protected array $sort = [
        'title' => 'title',
        'client' => 'client',
        'start_date' => 'start_date',
        'end_date' => 'end_date',
        'created_at' => 'created_at',
        'status' => 'status',
    ];

    public function status(string $status): void
    {
        $this->builder->where('status', $status);
    }

    public function client(string $client): void
    {
        $this->builder->where('client', 'like', "%{$client}%");
    }

    public function title(string $title): void
    {
        $this->builder->where('title', 'like', "%{$title}%");
    }

    public function startDateFrom(string $date): void
    {
        $this->builder->where('start_date', '>=', $date);
    }

    public function startDateTo(string $date): void
    {
        $this->builder->where('start_date', '<=', $date);
    }

    public function endDateFrom(string $date): void
    {
        $this->builder->where('end_date', '>=', $date);
    }

    public function endDateTo(string $date): void
    {
        $this->builder->where('end_date', '<=', $date);
    }
}
