<?php

namespace App\Policies\V1;

use App\Models\User;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function all(User $user): bool
    {
        return $user->can(\App\Enums\PermissionEnum::USER_READ->value);
    }

    public function show(User $user, User $model): bool
    {
        return $user->can(\App\Enums\PermissionEnum::USER_READ->value);
    }

    public function create(User $user): bool
    {   
        return $user->can(\App\Enums\PermissionEnum::USER_CREATE->value);
    }

    public function update(User $user, User $model): bool
    {
        return $user->can(\App\Enums\PermissionEnum::USER_UPDATE->value);
    }

    public function delete(User $user, User $model): bool
    {
        return $user->can(\App\Enums\PermissionEnum::USER_DELETE->value);
    }
}
