# API Documentation

## Table of Contents

1. [Authentication](#authentication)
   - Login
   - Refresh Token
   - Logout
   - Get Profile

2. [Users](#users)
   - List Users
   - Create User
   - Get User
   - Update User
   - Delete User

3. [Roles](#roles)
   - List Roles
   - Create Role
   - Get Role
   - Update Role
   - Delete Role
   - Assign Permissions

4. [Modules](#modules)
   - List Modules

5. [Projects](#projects)
   - List Projects
   - Create Project
   - Get Project
   - Update Project
   - Delete Project

6. [Tasks](#tasks)
   - List Tasks
   - Create Task
   - Get Task
   - Update Task
   - Delete Task

7. [Dashboard](#dashboard)
   - Get Dashboard Data

8. [Health](#health)
   - Health Check

---

## Base URL
`http://your-domain.com/api/v1`


## Authentication

### Login
Authenticate a user and receive an access token.

- **URL:** `/login`
- **Method:** `POST`
- **Auth Required:** No
- **Payload:**
  ```json
  {
    "email": "user@example.com",
    "password": "password123"
  }
  ```
- **Response:**
  ```json
  {
    "status": "success",
    "message": "Authentication successful.",
    "data": {
      "access_token": "eyJ0eX...",
      "token_type": "bearer",
      "expires_in": 3600,
      "user": { ... }
    }
  }
  ```

### Refresh Token
Refresh an expired access token using a refresh token cookie.

- **URL:** `/refresh`
- **Method:** `POST`
- **Auth Required:** Yes (JWT)
- **Cookies:** `refresh_token`
- **Response:**
  ```json
  {
    "status": "success",
    "message": "Authentication successful.",
    "data": {
      "access_token": "eyJ0eX...",
      "token_type": "bearer",
      "expires_in": 3600,
      "user": { ... }
    }
  }
  ```

### Logout
Invalidate the current token and clear the refresh token cookie.

- **URL:** `/logout`
- **Method:** `POST`
- **Auth Required:** Yes (JWT)
- **Response:**
  ```json
  {
    "status": "success",
    "message": "Successfully logged out."
  }
  ```

### Get Profile
Get the authenticated user's profile.

- **URL:** `/me`
- **Method:** `GET`
- **Auth Required:** Yes (JWT)
- **Response:**
  ```json
  {
    "status": "success",
    "message": "User profile retrieved successfully.",
    "data": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      ...
    }
  }
  ```

---

## Users

### List Users
Get a paginated list of users.

- **URL:** `/users`
- **Method:** `GET`
- **Auth Required:** Yes (JWT)
- **Query Parameters:**
  - `page`: Page number (default: 1)
  - `sort`: Sort field (e.g., `-id`, `name`)
  - `name`: Filter by name (e.g., `name=John`)
- **Response:**
  ```json
  {
    "data": [ ... ],
    "links": { ... },
    "meta": { ... }
  }
  ```

### Create User
Create a new user.

- **URL:** `/users`
- **Method:** `POST`
- **Auth Required:** Yes (JWT)
- **Payload:**
  ```json
  {
    "name": "Jane Doe",
    "email": "jane@example.com",
    "password": "password123"
  }
  ```
- **Response:**
  ```json
  {
    "status": "success",
    "message": "User created successfully",
    "data": { ... }
  }
  ```

### Get User
Get a specific user by ID.

- **URL:** `/users/{user}`
- **Method:** `GET`
- **Auth Required:** Yes (JWT)
- **Response:**
  ```json
  {
    "data": { ... }
  }
  ```

### Update User
Update an existing user.

- **URL:** `/users/{user}`
- **Method:** `PUT`/`PATCH`
- **Auth Required:** Yes (JWT)
- **Payload:**
  ```json
  {
    "name": "Jane Smith",
    "email": "jane.smith@example.com",
    "password": "newpassword123" // Optional
  }
  ```
- **Response:**
  ```json
  {
    "status": "success",
    "message": "User updated successfully",
    "data": { ... }
  }
  ```

### Delete User
Delete a user.

- **URL:** `/users/{user}`
- **Method:** `DELETE`
- **Auth Required:** Yes (JWT)
- **Response:**
  ```json
  {
    "status": "success",
    "message": "User deleted successfully"
  }
  ```

---

## Roles

### List Roles
Get a paginated list of roles.

- **URL:** `/roles`
- **Method:** `GET`
- **Auth Required:** Yes (JWT)
- **Response:**
  ```json
  {
    "data": [ ... ],
    "meta": { ... }
  }
  ```

### Create Role
Create a new role.

- **URL:** `/roles`
- **Method:** `POST`
- **Auth Required:** Yes (JWT)
- **Payload:**
  ```json
  {
    "name": "Editor",
    "description": "Can edit content" // Optional
  }
  ```
- **Response:**
  ```json
  {
    "status": "success",
    "message": "Role created successfully",
    "data": { ... }
  }
  ```

### Get Role
Get a specific role.

- **URL:** `/roles/{role}`
- **Method:** `GET`
- **Auth Required:** Yes (JWT)
- **Response:**
  ```json
  {
    "status": "success",
    "message": "Role fetched successfully",
    "data": { ... }
  }
  ```

### Update Role
Update an existing role.

- **URL:** `/roles/{role}`
- **Method:** `PUT`/`PATCH`
- **Auth Required:** Yes (JWT)
- **Payload:**
  ```json
  {
    "name": "Senior Editor",
    "description": "Can edit and publish content"
  }
  ```
- **Response:**
  ```json
  {
    "status": "success",
    "message": "Role updated successfully",
    "data": { ... }
  }
  ```

### Delete Role
Delete a role.

- **URL:** `/roles/{role}`
- **Method:** `DELETE`
- **Auth Required:** Yes (JWT)
- **Response:**
  ```json
  {
    "status": "success",
    "message": "Role deleted successfully"
  }
  ```

### Assign Permissions
Assign permissions to a role.

- **URL:** `/roles/{role}/permissions/assign`
- **Method:** `POST`
- **Auth Required:** Yes (JWT)
- **Payload:**
  ```json
  {
    "permissions": ["create_post", "edit_post"]
  }
  ```
- **Response:**
  ```json
  {
    "status": "success",
    "message": "Permissions assigned successfully",
    "data": { ... }
  }
  ```

---

## Modules

### List Modules
Get a list of all modules with their associated permissions.

- **URL:** `/modules`
- **Method:** `GET`
- **Auth Required:** Yes (JWT)
- **Permissions Required:** Role management permissions
- **Response:**
  ```json
  {
    "status": "success",
    "message": "Modules retrieved successfully.",
    "data": [
      {
        "id": 1,
        "name": "User Management",
        "key": "user-management",
        "permissions": [
          {
            "id": 1,
            "name": "create_user",
            "label": "Create User"
          },
          {
            "id": 2,
            "name": "edit_user",
            "label": "Edit User"
          },
          {
            "id": 3,
            "name": "delete_user",
            "label": "Delete User"
          },
          {
            "id": 4,
            "name": "view_user",
            "label": "View User"
          }
        ]
      },
      {
        "id": 2,
        "name": "Project Management",
        "key": "project-management",
        "permissions": [
          {
            "id": 5,
            "name": "create_project",
            "label": "Create Project"
          },
          {
            "id": 6,
            "name": "edit_project",
            "label": "Edit Project"
          },
          {
            "id": 7,
            "name": "delete_project",
            "label": "Delete Project"
          },
          {
            "id": 8,
            "name": "view_project",
            "label": "View Project"
          }
        ]
      }
    ]
  }
  ```

**Notes:**
- This endpoint returns all modules grouped with their permissions
- Useful for building permission management interfaces
- The `key` field is a URL-friendly slug of the module name

---

## Projects


### List Projects
Get a list of projects.

- **URL:** `/projects`
- **Method:** `GET`
- **Auth Required:** Yes (JWT)
- **Response:**
  ```json
  {
    "status": "success",
    "message": "Projects retrieved successfully.",
    "data": [ ... ]
  }
  ```

### Create Project
Create a new project.

- **URL:** `/projects`
- **Method:** `POST`
- **Auth Required:** Yes (JWT)
- **Payload:**
  ```json
  {
    "title": "Website Redesign",
    "client": "Acme Corp",
    "start_date": "2023-01-01",
    "end_date": "2023-06-30",
    "status": "active"
  }
  ```
- **Response:**
  ```json
  {
    "status": "success",
    "message": "Project created successfully.",
    "data": { ... }
  }
  ```

### Get Project
Get a specific project.

- **URL:** `/projects/{project}`
- **Method:** `GET`
- **Auth Required:** Yes (JWT)
- **Response:**
  ```json
  {
    "status": "success",
    "message": "Project retrieved successfully.",
    "data": { ... }
  }
  ```

### Update Project
Update an existing project.

- **URL:** `/projects/{project}`
- **Method:** `PUT`
- **Auth Required:** Yes (JWT)
- **Payload:**
  ```json
  {
    "title": "Website Redesign V2",
    "client": "Acme Corp",
    "start_date": "2023-01-01",
    "end_date": "2023-07-31",
    "status": "active"
  }
  ```
- **Response:**
  ```json
  {
    "status": "success",
    "message": "Project updated successfully.",
    "data": { ... }
  }
  ```

### Delete Project
Delete a project.

- **URL:** `/projects/{project}`
- **Method:** `DELETE`
- **Auth Required:** Yes (JWT)
- **Response:**
  ```json
  {
    "status": "success",
    "message": "Project deleted successfully."
  }
  ```

---

## Tasks

### List Tasks
Get a list of tasks.

- **URL:** `/tasks`
- **Method:** `GET`
- **Auth Required:** Yes (JWT)
- **Response:**
  ```json
  {
    "status": "success",
    "message": "Tasks retrieved successfully.",
    "data": [ ... ]
  }
  ```

### Create Task
Create a new task for a specific project.

- **URL:** `/projects/{project}/tasks`
- **Method:** `POST`
- **Auth Required:** Yes (JWT)
- **Payload:**
  ```json
  {
    "title": "Design Homepage",
    "description": "Create high-fidelity mockups for the homepage.",
    "status": "pending",
    "deadline": "2023-01-15",
    "assigned_user_id": 5
  }
  ```
- **Response:**
  ```json
  {
    "status": "success",
    "message": "Task created successfully. Notification sent to assigned user.",
    "data": { ... }
  }
  ```

### Get Task
Get a specific task.

- **URL:** `/tasks/{task}`
- **Method:** `GET`
- **Auth Required:** Yes (JWT)
- **Response:**
  ```json
  {
    "status": "success",
    "message": "Task retrieved successfully.",
    "data": { ... }
  }
  ```

### Update Task
Update an existing task.

- **URL:** `/tasks/{task}`
- **Method:** `PUT`
- **Auth Required:** Yes (JWT)
- **Payload:**
  ```json
  {
    "title": "Design Homepage",
    "description": "Create high-fidelity mockups for the homepage.",
    "status": "in_progress",
    "deadline": "2023-01-20",
    "assigned_user_id": 5
  }
  ```
- **Response:**
  ```json
  {
    "status": "success",
    "message": "Task updated successfully.",
    "data": { ... }
  }
  ```

### Delete Task
Delete a task.

- **URL:** `/tasks/{task}`
- **Method:** `DELETE`
- **Auth Required:** Yes (JWT)
- **Response:**
  ```json
  {
    "status": "success",
    "message": "Task deleted successfully."
  }
  ```

---

## Dashboard

### Get Dashboard Data
Get comprehensive dashboard statistics including project and task metrics, recent items, and overdue tracking.

- **URL:** `/dashboard`
- **Method:** `GET`
- **Auth Required:** Yes (JWT)
- **Response:**
  ```json
  {
    "status": "success",
    "message": "Dashboard data retrieved successfully",
    "data": {
      "statistics": {
        "total_projects": 25,
        "total_tasks": 150,
        "total_users": 10,
        "active_projects": 8
      },
      "project_statistics": {
        "by_status": {
          "pending": 5,
          "in_progress": 8,
          "completed": 10,
          "cancelled": 2
        },
        "overdue": 3
      },
      "task_statistics": {
        "by_status": {
          "pending": 45,
          "in_progress": 60,
          "completed": 40,
          "cancelled": 5
        },
        "overdue": 12
      },
      "user_tasks": {
        "total": 15,
        "by_status": {
          "pending": 5,
          "in_progress": 8,
          "completed": 2,
          "cancelled": 0
        },
        "overdue": 2
      },
      "recent_projects": [
        {
          "id": 1,
          "title": "Website Redesign",
          "client": "Acme Corp",
          "status": "in_progress",
          "start_date": "2023-01-01",
          "end_date": "2023-06-30",
          "completion_percentage": 65.5,
          "tasks_count": 20
        }
      ],
      "recent_tasks": [
        {
          "id": 1,
          "title": "Design Homepage",
          "status": "in_progress",
          "deadline": "2023-01-15 17:00:00",
          "is_overdue": false,
          "project": {
            "id": 1,
            "title": "Website Redesign"
          },
          "assigned_user": {
            "id": 5,
            "name": "John Doe"
          }
        }
      ],
      "overdue_items": {
        "projects": [
          {
            "id": 3,
            "title": "Mobile App",
            "client": "TechCo",
            "end_date": "2023-05-15",
            "days_overdue": 45
          }
        ],
        "tasks": [
          {
            "id": 12,
            "title": "Fix Login Bug",
            "deadline": "2023-06-01 12:00:00",
            "days_overdue": 15,
            "project": {
              "id": 2,
              "title": "Bug Fixes"
            },
            "assigned_user": {
              "id": 3,
              "name": "Jane Smith"
            }
          }
        ]
      }
    }
  }
  ```

**Response Fields:**

- `statistics`: Overall system statistics
  - `total_projects`: Total number of projects in the system
  - `total_tasks`: Total number of tasks in the system
  - `total_users`: Total number of registered users
  - `active_projects`: Number of projects with status "in_progress"

- `project_statistics`: Project-specific metrics
  - `by_status`: Count of projects grouped by status (pending, in_progress, completed, cancelled)
  - `overdue`: Number of projects past their end_date that are not completed or cancelled

- `task_statistics`: Task-specific metrics
  - `by_status`: Count of tasks grouped by status (pending, in_progress, completed, cancelled)
  - `overdue`: Number of tasks past their deadline that are not completed or cancelled

- `user_tasks`: Statistics for the authenticated user's assigned tasks
  - `total`: Total number of tasks assigned to the current user
  - `by_status`: Breakdown of user's tasks by status
  - `overdue`: Number of user's overdue tasks

- `recent_projects`: Array of the 5 most recently created projects
  - Includes completion percentage calculated from task completion ratio
  - Includes total task count for each project

- `recent_tasks`: Array of the 10 most recently created tasks
  - Includes project and assigned user information
  - Includes `is_overdue` boolean flag

- `overdue_items`: Lists of overdue projects and tasks
  - `projects`: Overdue projects with days_overdue count
  - `tasks`: Overdue tasks with days_overdue count and related information

**Notes:**
- This endpoint provides a comprehensive overview for dashboard displays
- All date fields are in ISO 8601 format
- The `days_overdue` field shows the number of days past the deadline/end_date
- Completion percentage is calculated as (completed_tasks / total_tasks) * 100
- User-specific statistics are based on the authenticated user's ID


---

## Health

### Health Check
Check if the API is running.

- **URL:** `/health`
- **Method:** `GET`
- **Auth Required:** No
- **Response:**
  ```json
  {
    "status": "success",
    "message": "Healthy",
    "data": {
      "timestamp": "2023-10-27T10:00:00.000000Z"
    }
  }
  ```
