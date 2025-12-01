<?php

namespace App\Filters\V1;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class QueryFilter
{
    protected Builder $builder;
    protected Request $request;
    protected array $sort = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public static function init(): static
    {
        return new static(request());
    }

    public function getSort(): array
    {
        return $this->sort;
    }

    public function apply(Builder $builder): void
    {
        $this->builder = $builder;

        if($this->request->has('filters') && is_array($this->request->get('filters')) && !empty($this->request->get('filters'))) {
            $this->filter($this->request->get('filters'));
        }

        if($this->request->has('sort') && !empty($this->request->get('sort'))) {
            $this->sort($this->request->get('sort'));
        }

    }

    private function filter(array $filters): void
    {
        foreach ($filters as $key => $value) {
            if(method_exists($this, $key)){
                $this->$key($value);
            }
        }
    }

    private function sort(string $sort = ''): void
    {
        $sorts = explode(',',$sort);

        foreach ($sorts as $sort) {

            // Determine the direction (ascending by default)
            $direction = 'asc';

            // Check if the direction is specified (e.g., '-date' for descending)
            if (str_starts_with($sort, '-')) {
                $direction = 'desc';
                $sort = substr($sort, 1); // Remove the '-' sign
            }

            if(!in_array($sort, $this->sort) && !array_key_exists($sort, $this->sort)) {
                continue;
            }

            $column = $this->sort[$sort] ?? $sort;

            // Apply sorting to the query
            $this->builder->orderBy($column, $direction);
        }
    }

}
