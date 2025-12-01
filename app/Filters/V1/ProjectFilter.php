<?php

namespace App\Filters\V1;

use Illuminate\Database\Eloquent\Builder;

/**
 * Query filter for Project model
 * 
 * Provides filtering and sorting capabilities for project queries.
 */
class ProjectFilter extends QueryFilter
{
    /**
     * Sortable fields
     *
     * @var array<string, string>
     */
    protected array $sort = [
        'title' => 'title',
        'client' => 'client',
        'start_date' => 'start_date',
        'end_date' => 'end_date',
        'created_at' => 'created_at',
        'status' => 'status',
    ];

    /**
     * Filter by project status
     *
     * @param string $status
     * @return void
     */
    public function status(string $status): void
    {
        $this->builder->where('status', $status);
    }

    /**
     * Search by client name
     *
     * @param string $client
     * @return void
     */
    public function client(string $client): void
    {
        $this->builder->where('client', 'like', "%{$client}%");
    }

    /**
     * Search by project title
     *
     * @param string $title
     * @return void
     */
    public function title(string $title): void
    {
        $this->builder->where('title', 'like', "%{$title}%");
    }

    /**
     * Filter by start date (from)
     *
     * @param string $date
     * @return void
     */
    public function startDateFrom(string $date): void
    {
        $this->builder->where('start_date', '>=', $date);
    }

    /**
     * Filter by start date (to)
     *
     * @param string $date
     * @return void
     */
    public function startDateTo(string $date): void
    {
        $this->builder->where('start_date', '<=', $date);
    }

    /**
     * Filter by end date (from)
     *
     * @param string $date
     * @return void
     */
    public function endDateFrom(string $date): void
    {
        $this->builder->where('end_date', '>=', $date);
    }

    /**
     * Filter by end date (to)
     *
     * @param string $date
     * @return void
     */
    public function endDateTo(string $date): void
    {
        $this->builder->where('end_date', '<=', $date);
    }

    /**
     * Filter by creator user ID
     *
     * @param int $userId
     * @return void
     */
    public function createdBy(int $userId): void
    {
        $this->builder->where('created_by', $userId);
    }
}
