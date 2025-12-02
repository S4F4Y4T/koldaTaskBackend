<?php

namespace App\Traits\V1;

trait PaginateAll
{
    public function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null, $total = null): \Illuminate\Database\Eloquent\Collection|\Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $perPage = request()->input('show', $perPage ?: 15);
        if ($perPage == -1) {
            return $this->get($columns);
        }

        return parent::paginate($perPage, $columns, $pageName, $page);
    }
}
