<?php

namespace App\Enums;

enum PermissionEnum: string
{
    // User Management
    case USER_READ = 'user_read';
    case USER_CREATE = 'user_create';
    case USER_UPDATE = 'user_update';
    case USER_DELETE = 'user_delete';

    // Role Management
    case ROLE_READ = 'role_read';
    case ROLE_CREATE = 'role_create';
    case ROLE_UPDATE = 'role_update';
    case ROLE_DELETE = 'role_delete';
    case ROLE_ASSIGN_PERMISSION = 'role_assign_permission';

    // Project Management
    case PROJECT_READ = 'project_read';
    case PROJECT_CREATE = 'project_create';
    case PROJECT_UPDATE = 'project_update';
    case PROJECT_DELETE = 'project_delete';

    // Task Management
    case TASK_READ = 'task_read';
    case TASK_CREATE = 'task_create';
    case TASK_UPDATE = 'task_update';
    case TASK_DELETE = 'task_delete';



    public static function modules(): array
    {
        return [
            'User Management' => [
                self::USER_READ, self::USER_CREATE, self::USER_UPDATE, self::USER_DELETE
            ],
            'Role Management' => [
                self::ROLE_READ, self::ROLE_CREATE, self::ROLE_UPDATE, self::ROLE_DELETE, self::ROLE_ASSIGN_PERMISSION
            ],
            'Project Management' => [
                self::PROJECT_READ, self::PROJECT_CREATE, self::PROJECT_UPDATE, self::PROJECT_DELETE
            ],
            'Task Management' => [
                self::TASK_READ, self::TASK_CREATE, self::TASK_UPDATE, self::TASK_DELETE
            ],

        ];
    }
}
