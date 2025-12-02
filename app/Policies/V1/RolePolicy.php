<?php

namespace App\Policies\V1;

use App\Enums\PermissionEnum;
use App\Models\User;

class RolePolicy
{
    public function all(User $user): bool
    {
        return $user->can(PermissionEnum::ROLE_READ->value);
    }

    public function show(User $user, $role): bool
    {
        return $user->can(PermissionEnum::ROLE_READ->value);
    }

    public function create(User $user): bool
    {
        return $user->can(PermissionEnum::ROLE_CREATE->value);
    }

    public function update(User $user): bool
    {
        return $user->can(PermissionEnum::ROLE_UPDATE->value);
    }

    public function delete(User $user): bool
    {
        return $user->can(PermissionEnum::ROLE_DELETE->value);
    }
}
