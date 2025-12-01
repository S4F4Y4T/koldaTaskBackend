<?php

namespace App\Policies\V1;

use App\Models\User;
use App\Enums\PermissionEnum;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function all(User $user): bool
    {
        return $user->can(PermissionEnum::USER_READ->value);
    }

    public function show(User $user, User $model): bool
    {
        return $user->can(PermissionEnum::USER_READ->value);
    }

    public function create(User $user): bool
    {   
        return $user->can(PermissionEnum::USER_CREATE->value);
    }

    public function update(User $user, User $model): bool
    {
        return $user->can(PermissionEnum::USER_UPDATE->value);
    }

    public function delete(User $user, User $model): bool
    {
        return $user->can(PermissionEnum::USER_DELETE->value);
    }
}
