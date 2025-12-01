<?php

namespace App\Builders;

use App\Traits\V1\PaginateAll;
use Illuminate\Database\Eloquent\Builder;

class BaseQueryBuilder extends Builder
{
    use PaginateAll;
}
