<?php

namespace App\Policies\V1;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;

class RolePolicy
{
    /**
     */
    public function all(User $user): bool
    {
        return $user->can(\App\Enums\PermissionEnum::ROLE_READ->value);
    }

    public function show(User $user, $role): bool
    {
        return $user->can(\App\Enums\PermissionEnum::ROLE_READ->value);
    }

    public function create(User $user): bool
    {
        return $user->can(\App\Enums\PermissionEnum::ROLE_CREATE->value);
    }

    public function update(User $user): bool
    {
        return $user->can(\App\Enums\PermissionEnum::ROLE_UPDATE->value);
    }

    public function delete(User $user): bool
    {
        return $user->can(\App\Enums\PermissionEnum::ROLE_DELETE->value);
    }
}
