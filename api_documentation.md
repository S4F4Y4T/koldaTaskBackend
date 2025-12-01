# API Documentation

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
  - `filter[name]`: Filter by name
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
    "priority": "high",
    "due_date": "2023-01-15",
    "assigned_to": 5
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
    "priority": "high",
    "due_date": "2023-01-20",
    "assigned_to": 5
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
Get aggregated dashboard statistics.

- **URL:** `/dashboard`
- **Method:** `GET`
- **Auth Required:** Yes (JWT)
- **Response:**
  ```json
  {
    "status": "success",
    "message": "Dashboard data",
    "data": [ ... ]
  }
  ```

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
