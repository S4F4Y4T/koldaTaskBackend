<?php

namespace App\Models;

use App\Builders\BaseQueryBuilder;
use App\Filters\V1\QueryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Main extends Model
{
    use HasFactory;

    public function scopeFilter(Builder $query, QueryFilter $filter): void
    {
        $filter->apply($query);
    }

    public function newEloquentBuilder($query): BaseQueryBuilder
    {
        return new BaseQueryBuilder($query);
    }
}
