<?php

namespace App\Policies\V1;

use App\Enums\PermissionEnum;
use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    public function all(User $user): bool
    {
        return $user->can(PermissionEnum::PROJECT_READ->value);
    }

    public function view(User $user, Project $project): bool
    {
        return $user->can(PermissionEnum::PROJECT_READ->value);
    }

    public function create(User $user): bool
    {
        return $user->can(PermissionEnum::PROJECT_CREATE->value);
    }

    public function update(User $user, Project $project): bool
    {
        return $user->can(PermissionEnum::PROJECT_UPDATE->value);
    }

    public function delete(User $user, Project $project): bool
    {
        return $user->can(PermissionEnum::PROJECT_DELETE->value);
    }
}
