<?php

namespace App\Http\Controllers\Api;

use App\Traits\V1\ApiResponse;
use Illuminate\Support\Facades\Gate;

abstract class Controller
{
    use ApiResponse;

    public function isAuthorized($ability, $model = null): void
    {
        if (! $model) {
            $model = $this->policyModel;
        }
        Gate::authorize($ability, $model);
    }
}
