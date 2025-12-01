<?php

namespace App\Policies\V1;

use App\Models\Task;
use App\Models\User;
use App\Enums\PermissionEnum;

class TaskPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can(PermissionEnum::TASK_READ->value);
    }

    public function view(User $user, Task $task): bool
    {
        return $user->can(PermissionEnum::TASK_READ->value);
    }

    public function create(User $user): bool
    {
        return $user->can(PermissionEnum::TASK_CREATE->value);
    }

    public function update(User $user, Task $task): bool
    {
        return $user->can(PermissionEnum::TASK_UPDATE->value);
    }

    public function delete(User $user, Task $task): bool
    {
        return $user->can(PermissionEnum::TASK_DELETE->value);
    }
}
