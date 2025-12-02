# KoldaTech Backend - Technical Documentation

## Table of Contents

1. [Project Overview](#project-overview)
2. [Technology Stack](#technology-stack)
3. [Architecture & Design Patterns](#architecture--design-patterns)
4. [Project Structure](#project-structure)
5. [Core Features](#core-features)
6. [Setup Instructions](#setup-instructions)
7. [Docker Setup](#docker-setup)
8. [Database Schema](#database-schema)
9. [API Documentation](#api-documentation)
10. [Testing](#testing)
11. [Development Guidelines](#development-guidelines)
12. [Deployment](#deployment)

---

## Project Overview

**KoldaTech Backend** is a modern RESTful API built with Larave, designed for project and task management with comprehensive authentication, authorization, and role-based access control (RBAC).

### Key Highlights

- **Framework**: Laravel
- **PHP Version**: 8.3
- **Authentication**: JWT (JSON Web Tokens)
- **Database**: MySQL 8.0
- **Testing**: Pest PHP
- **Containerization**: Docker & Docker Compose
- **Architecture**: Service Pattern with DTOs

---

## Technology Stack

### Backend Framework
- **Laravel** - Modern PHP framework
- **PHP 8.3** - Latest PHP version with performance improvements

### Authentication & Security
- **tymon/jwt-auth 2.1** - JWT authentication
- Custom middleware for API authentication
- Role-based access control (RBAC)
- Policy-based authorization

### Database
- **MySQL 8.0** - Primary database
- **Eloquent ORM** - Database abstraction layer
- **Migrations** - Version control for database schema

### Testing
- **Pest PHP 3.7** - Modern testing framework
- **Pest Laravel Plugin** - Laravel-specific testing utilities
- **Pest Stressless Plugin** - Performance testing
- **PHPUnit** - Underlying test runner

### Development Tools
- **Laravel Pint** - Code style fixer
- **Laravel Tinker** - REPL for Laravel
- **Faker** - Test data generation
- **Mockery** - Mocking framework

### DevOps
- **Docker** - Containerization
- **Docker Compose** - Multi-container orchestration
- **Nginx** - Web server
- **PHP-FPM** - FastCGI Process Manager

---

## Architecture & Design Patterns

### 1. **Layered Architecture**

```
┌─────────────────────────────────────┐
│         Routes (API Gateway)        │
├─────────────────────────────────────┤
│         Controllers Layer           │
│  (HTTP Request/Response Handling)   │
├─────────────────────────────────────┤
│         Services Layer              │
│    (Business Logic & Workflows)     │
├─────────────────────────────────────┤
│         Models Layer                │
│   (Data Access & Relationships)     │
├─────────────────────────────────────┤
│         Database Layer              │
│      (MySQL with Eloquent ORM)      │
└─────────────────────────────────────┘
```

### 2. **Design Patterns Used**

#### **Service Pattern**
- Business logic encapsulated in service classes
- Examples: `AuthenticationService`, `DashboardService`
- Located in: `app/Services/V1/`

#### **Repository Pattern (via Eloquent)**
- Data access abstraction through Eloquent models
- Custom query builders for complex queries
- Located in: `app/Models/`

#### **DTO Pattern (Data Transfer Objects)**
- Type-safe data transfer between layers
- Request validation and transformation
- Located in: `app/DTOs/V1/`
- Examples: `UserDTO`, `ProjectDTO`, `TaskDTO`, `RoleDTO`

#### **Factory Pattern**
- Model factories for testing and seeding
- Located in: `database/factories/`

#### **Observer Pattern**
- Event-driven architecture for task notifications
- Events: `TaskCreated`
- Listeners: `SendTaskCreatedNotification`
- Located in: `app/Events/`, `app/Listeners/`

#### **Strategy Pattern (Filters)**
- Dynamic query filtering
- Located in: `app/Filters/V1/`
- Examples: `UserFilter`, `ProjectFilter`, `TaskFilter`, `RoleFilter`

#### **Policy Pattern**
- Authorization logic separation
- Located in: `app/Policies/V1/`
- Examples: `UserPolicy`, `ProjectPolicy`, `TaskPolicy`, `RolePolicy`

#### **Builder Pattern**
- Custom query builders for enhanced functionality
- Located in: `app/Builders/`
- Example: `BaseQueryBuilder`

### 3. **Dependency Injection**
- Constructor injection throughout the application
- Service container for automatic resolution
- Interface binding for flexibility

---

## Project Structure

```
koldaTaskBackend/
├── app/
│   ├── Builders/              # Custom query builders
│   │   └── BaseQueryBuilder.php
│   ├── DTOs/                  # Data Transfer Objects
│   │   └── V1/
│   │       ├── ProjectDTO.php
│   │       ├── RoleDTO.php
│   │       ├── TaskDTO.php
│   │       └── UserDTO.php
│   ├── Enums/                 # Enumeration classes
│   │   ├── ProjectStatus.php
│   │   ├── TaskPriority.php
│   │   └── TaskStatus.php
│   ├── Events/                # Event classes
│   │   └── TaskCreated.php
│   ├── Filters/               # Query filters
│   │   └── V1/
│   │       ├── ProjectFilter.php
│   │       ├── QueryFilter.php (Base)
│   │       ├── RoleFilter.php
│   │       ├── TaskFilter.php
│   │       └── UserFilter.php
│   ├── Helpers/               # Helper functions
│   │   └── generalHelper.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Api/
│   │   │       ├── Controller.php (Base)
│   │   │       └── V1/
│   │   │           ├── AuthenticationController.php
│   │   │           ├── DashboardController.php
│   │   │           ├── ModuleController.php
│   │   │           ├── ProjectController.php
│   │   │           ├── RoleController.php
│   │   │           ├── TaskController.php
│   │   │           └── UserController.php
│   │   ├── Middleware/        # Custom middleware
│   │   │   ├── AlreadyLoggedIn.php
│   │   │   └── JwtMiddleware.php
│   │   ├── Requests/          # Form request validation
│   │   │   └── V1/
│   │   │       ├── Authentication/
│   │   │       ├── Project/
│   │   │       ├── Role/
│   │   │       ├── Task/
│   │   │       └── User/
│   │   └── Resources/         # API resources
│   │       └── V1/
│   │           ├── ModuleResource.php
│   │           ├── PermissionResource.php
│   │           ├── ProjectResource.php
│   │           ├── RoleResource.php
│   │           ├── TaskResource.php
│   │           └── UserResource.php
│   ├── Jobs/                  # Queued jobs
│   │   └── SendTaskNotification.php
│   ├── Listeners/             # Event listeners
│   │   └── SendTaskCreatedNotification.php
│   ├── Mail/                  # Mailable classes
│   │   └── TaskAssignedMail.php
│   ├── Models/                # Eloquent models
│   │   ├── Main.php (Base Model)
│   │   ├── Module.php
│   │   ├── Permission.php
│   │   ├── Project.php
│   │   ├── Role.php
│   │   ├── Task.php
│   │   └── User.php
│   ├── Policies/              # Authorization policies
│   │   ├── ProjectPolicy.php
│   │   ├── RolePolicy.php
│   │   ├── TaskPolicy.php
│   │   └── UserPolicy.php
│   ├── Providers/             # Service providers
│   │   └── AppServiceProvider.php
│   ├── Services/              # Business logic services
│   │   └── V1/
│   │       ├── AuthenticationService.php
│   │       └── DashboardService.php
│   └── Traits/                # Reusable traits
│       └── V1/
│           ├── ApiResponse.php
│           ├── Authorization.php
│           └── Authorizer.php
├── bootstrap/                 # Application bootstrap
├── config/                    # Configuration files
│   ├── app.php
│   ├── auth.php
│   ├── database.php
│   ├── jwt.php               # JWT configuration
│   └── ...
├── database/
│   ├── factories/            # Model factories
│   ├── migrations/           # Database migrations
│   └── seeders/              # Database seeders
│       ├── DatabaseSeeder.php
│       ├── ModulePermissionSeeder.php
│       ├── RoleSeeder.php
│       └── UserSeeder.php
├── public/                   # Public assets
├── resources/
│   └── views/
│       └── emails/           # Email templates
│           └── task-assigned.blade.php
├── routes/
│   ├── api.php              # API routes (main)
│   ├── console.php          # Console routes
│   ├── web.php              # Web routes
│   └── v1/
│       └── api.php          # V1 API routes
├── storage/                 # Storage directory
├── tests/
│   ├── Feature/             # Feature tests
│   │   ├── Architecture/    # Architecture tests
│   │   └── Http/            # HTTP tests
│   ├── Unit/                # Unit tests
│   ├── Pest.php             # Pest configuration
│   └── TestCase.php         # Base test case
├── .env.example             # Environment variables template
├── Dockerfile               # Docker configuration
├── docker-compose.yml       # Docker Compose configuration
├── nginx/
│   └── default.conf         # Nginx configuration
├── api_documentation.md     # API documentation
├── composer.json            # PHP dependencies
├── phpunit.xml              # PHPUnit configuration
└── README.md                # Project readme
```

---

## Core Features

### 1. **Authentication System**

#### JWT-Based Authentication
- **Login**: Email/password authentication with JWT token generation
- **Refresh Token**: Separate refresh token stored in HTTP-only cookies
- **Logout**: Token blacklisting for secure logout
- **Token Management**: Automatic token refresh and expiration handling

#### Security Features
- Password hashing with bcrypt
- Token blacklisting
- Grace period for concurrent requests
- HTTP-only cookies for refresh tokens
- Environment-based secure cookie settings

### 2. **Authorization System**

#### Role-Based Access Control (RBAC)
- **Roles**: Admin, Manager, User (customizable)
- **Permissions**: Granular permission system
- **Modules**: Permissions grouped by modules (User Management, Project Management, etc.)

#### Polymorphic Permission System
- Users can have direct permissions
- Roles can have permissions
- Users inherit permissions from roles
- Uses `model_has_permissions` polymorphic table

#### Policy-Based Authorization
- Resource-level authorization
- Policy classes for each resource (User, Project, Task, Role)
- Automatic policy resolution via `Authorizer` trait

### 3. **User Management**

#### Features
- CRUD operations for users
- Role assignment
- Direct permission assignment
- User filtering and sorting
- Pagination support

#### User Attributes
- Name, email, password
- Email verification support
- Timestamps (created_at, updated_at)

### 4. **Project Management**

#### Features
- CRUD operations for projects
- Project status tracking (Pending, In Progress, Completed, Cancelled)
- Client information
- Date range (start_date, end_date)
- Task association
- Completion percentage calculation
- Overdue detection

#### Project Attributes
- Title, client
- Start date, end date
- Status (enum)
- Timestamps

#### Business Logic
- Automatic completion percentage based on task completion
- Overdue project detection
- Status-based filtering
- Date range queries

### 5. **Task Management**

#### Features
- CRUD operations for tasks
- Task assignment to users
- Project association
- Status tracking (Pending, In Progress, Completed, Cancelled)
- Priority levels (Low, Medium, High, Critical)
- Deadline management
- Email notifications on task creation

#### Task Attributes
- Title, description
- Status (enum)
- Priority (enum)
- Deadline (datetime)
- Assigned user
- Project association
- Timestamps

#### Business Logic
- Automatic email notification to assigned user
- Overdue task detection
- Status-based filtering
- User-specific task queries

### 6. **Dashboard Analytics**

#### Statistics Provided
- **Overall Statistics**
  - Total projects, tasks, users
  - Active projects count

- **Project Statistics**
  - Breakdown by status
  - Overdue projects count

- **Task Statistics**
  - Breakdown by status
  - Overdue tasks count

- **User-Specific Statistics**
  - User's assigned tasks
  - User's task breakdown by status
  - User's overdue tasks

- **Recent Items**
  - Last 5 projects with completion percentage
  - Last 10 tasks with details

- **Overdue Tracking**
  - Overdue projects with days overdue
  - Overdue tasks with days overdue

### 7. **Module & Permission Management**

#### Modules
- Logical grouping of permissions
- Examples: User Management, Project Management, Task Management
- Each module contains related permissions

#### Permissions
- Granular access control
- Naming convention: `{action}_{resource}` (e.g., `create_user`, `edit_project`)
- Can be assigned to roles or users directly

### 8. **Email Notifications**

#### Task Assignment Notifications
- Automatic email on task creation
- Sent to assigned user
- Contains task details and project information
- Queued for asynchronous processing

#### Email Configuration
- SMTP support (Mailtrap for development)
- Customizable email templates (Blade)
- Queue support for performance

### 9. **Advanced Filtering & Sorting**

#### Query Filters
- Dynamic filtering based on request parameters
- Supports multiple filter types:
  - Exact match
  - Partial match (LIKE)
  - Date ranges
  - Status filtering
  - Relationship filtering

#### Sorting
- Multi-column sorting
- Ascending/descending order
- Prefix-based sorting (`-` for descending)

#### Pagination
- Automatic pagination for list endpoints
- Configurable page size
- Metadata included in responses

### 10. **API Versioning**

#### Version 1 (V1)
- All current endpoints under `/api/v1/`
- Separate routing file for version isolation
- Version-specific controllers, DTOs, and resources

---

## Setup Instructions

### Prerequisites

- **Docker** (v20.10+)
- **Docker Compose** (v2.0+)
- **Git**

### Local Setup with Docker

#### 1. Clone the Repository

```bash
git clone https://github.com/S4F4Y4T/koldaTaskBackend.git
cd koldaTaskBackend
```

#### 2. Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Update the following variables in .env if needed:
# - DB_HOST=db (must match docker-compose service name)
# - DB_DATABASE=koldatech
# - DB_USERNAME=koldatech_user
# - DB_PASSWORD=password
# - APP_URL=http://127.0.0.1:8080
```

#### 3. Build and Start Docker Containers

```bash
# Build and start all containers
docker-compose up -d --build

# Verify containers are running
docker-compose ps
```

You should see 4 containers running:
- `koldatech_backend` (PHP-FPM)
- `koldatech_nginx` (Nginx web server)
- `koldatech_mysql` (MySQL database)
- `koldatech_phpmyadmin` (phpMyAdmin)

#### 4. Install Dependencies

```bash
# Enter the app container
docker-compose exec app bash

# Install Composer dependencies
composer install

# Generate application key
php artisan key:generate

# Generate JWT secret
php artisan jwt:secret
```

#### 5. Database Setup

```bash
# Still inside the app container

# Run migrations
php artisan migrate

# Seed the database with initial data
php artisan db:seed

# Or run migrations and seed in one command
php artisan migrate:fresh --seed
```

#### 6. Set Permissions

```bash
# Still inside the app container
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Exit container
exit
```

#### 7. Access the Application

- **API Base URL**: http://127.0.0.1:8080/api/v1
- **phpMyAdmin**: http://127.0.0.1:6080
  - Server: `db`
  - Username: `root`
  - Password: `rootpassword`

#### 8. Test the Setup

```bash
# Health check
curl http://127.0.0.1:8080/api/v1/health

# Expected response:
# {
#   "status": "success",
#   "message": "Healthy",
#   "data": {
#     "timestamp": "2024-12-02T08:00:00.000000Z"
#   }
# }
```

### Default Credentials

After seeding, you can login with:

```json
{
  "email": "test@example.com",
  "password": "password"
}
```

---

## Docker Setup

### Docker Architecture

```
┌─────────────────────────────────────────────────────┐
│                   Docker Network                     │
│                    (koldatech)                       │
│                                                      │
│  ┌──────────────┐  ┌──────────────┐  ┌───────────┐ │
│  │    Nginx     │  │   PHP-FPM    │  │   MySQL   │ │
│  │   (Port 80)  │◄─┤  (Port 9000) │◄─┤ (Port 3306│ │
│  └──────────────┘  └──────────────┘  └───────────┘ │
│         │                                     │      │
│         │                                     │      │
│  ┌──────▼──────┐                      ┌──────▼────┐ │
│  │   Host      │                      │ phpMyAdmin│ │
│  │  Port 8080  │                      │ Port 6080 │ │
│  └─────────────┘                      └───────────┘ │
└─────────────────────────────────────────────────────┘
```

### Container Details

#### 1. **App Container** (`koldatech_backend`)
- **Base Image**: `php:8.3-fpm`
- **Purpose**: Runs PHP-FPM for processing PHP code
- **Extensions**:
  - GD (image processing)
  - PDO MySQL (database)
  - Zip (compression)
  - EXIF (image metadata)
  - HTTP (PECL extension)
- **Volume**: `.:/var/www` (live code sync)

#### 2. **Nginx Container** (`koldatech_nginx`)
- **Base Image**: `nginx:latest`
- **Purpose**: Web server and reverse proxy
- **Ports**: `8080:80` (host:container)
- **Configuration**: `./nginx/default.conf`
- **Max Upload**: 1000MB

#### 3. **Database Container** (`koldatech_mysql`)
- **Base Image**: `mysql:8.0`
- **Purpose**: MySQL database server
- **Credentials**:
  - Database: `koldatech`
  - User: `koldatech_user`
  - Password: `password`
  - Root Password: `rootpassword`
- **Volume**: Named volume `koldatech` for data persistence

#### 4. **phpMyAdmin Container** (`koldatech_phpmyadmin`)
- **Base Image**: `phpmyadmin/phpmyadmin`
- **Purpose**: Database management GUI
- **Ports**: `6080:80` (host:container)
- **Access**: http://127.0.0.1:6080

### Docker Commands

```bash
# Start containers
docker-compose up -d

# Stop containers
docker-compose down

# View logs
docker-compose logs -f

# View logs for specific service
docker-compose logs -f app

# Restart a service
docker-compose restart app

# Rebuild containers
docker-compose up -d --build

# Execute command in app container
docker-compose exec app php artisan migrate

# Access app container shell
docker-compose exec app bash

# Access database container
docker-compose exec db mysql -u root -p

# Remove containers and volumes
docker-compose down -v
```

### Troubleshooting Docker

#### Container won't start
```bash
# Check container logs
docker-compose logs app

# Check if port is already in use
lsof -i :8080
lsof -i :6080
```

#### Permission issues
```bash
# Fix storage permissions
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

#### Database connection issues
```bash
# Verify database is running
docker-compose ps db

# Check database logs
docker-compose logs db

# Verify .env DB_HOST is set to 'db'
```

---

## Database Schema

### Entity Relationship Diagram

```
┌─────────────┐         ┌──────────────┐         ┌─────────────┐
│    Users    │────────▶│  user_role   │◀────────│    Roles    │
└─────────────┘         └──────────────┘         └─────────────┘
      │                                                  │
      │                                                  │
      │                 ┌──────────────────────┐        │
      └────────────────▶│ model_has_permissions│◀───────┘
                        └──────────────────────┘
                                   │
                                   │
                                   ▼
                        ┌──────────────────┐
                        │   Permissions    │
                        └──────────────────┘
                                   │
                                   │
                                   ▼
                        ┌──────────────────┐
                        │     Modules      │
                        └──────────────────┘

┌─────────────┐         ┌──────────────┐         ┌─────────────┐
│  Projects   │────────▶│    Tasks     │◀────────│    Users    │
│             │  1:N    │              │   N:1   │ (assigned)  │
└─────────────┘         └──────────────┘         └─────────────┘
```

### Tables

#### **users**
```sql
- id (bigint, PK)
- name (varchar)
- email (varchar, unique)
- email_verified_at (timestamp, nullable)
- password (varchar)
- remember_token (varchar, nullable)
- created_at (timestamp)
- updated_at (timestamp)
```

#### **roles**
```sql
- id (bigint, PK)
- name (varchar, unique)
- created_at (timestamp)
- updated_at (timestamp)
```

#### **user_role** (Pivot)
```sql
- user_id (bigint, FK → users.id)
- role_id (bigint, FK → roles.id)
```

#### **permissions**
```sql
- id (bigint, PK)
- name (varchar, unique)
- module_id (bigint, FK → modules.id, nullable)
- created_at (timestamp)
- updated_at (timestamp)
```

#### **modules**
```sql
- id (bigint, PK)
- name (varchar)
- created_at (timestamp)
- updated_at (timestamp)
```

#### **model_has_permissions** (Polymorphic)
```sql
- permission_id (bigint, FK → permissions.id)
- model_type (varchar) -- User or Role
- model_id (bigint) -- ID of User or Role
```

#### **projects**
```sql
- id (bigint, PK)
- title (varchar)
- client (varchar)
- start_date (date)
- end_date (date)
- status (enum: pending, in_progress, completed, cancelled)
- created_at (timestamp)
- updated_at (timestamp)
```

#### **tasks**
```sql
- id (bigint, PK)
- project_id (bigint, FK → projects.id)
- title (varchar)
- description (text, nullable)
- status (enum: pending, in_progress, completed, cancelled)
- deadline (datetime)
- assigned_user_id (bigint, FK → users.id)
- created_at (timestamp)
- updated_at (timestamp)
```

---

## API Documentation

Comprehensive API documentation is available in `api_documentation.md`.

### Quick Reference

#### Authentication Endpoints
- `POST /api/v1/login` - User login
- `POST /api/v1/refresh` - Refresh access token
- `POST /api/v1/logout` - User logout
- `GET /api/v1/me` - Get authenticated user

#### Resource Endpoints
- `/api/v1/users` - User management
- `/api/v1/roles` - Role management
- `/api/v1/modules` - Module & permission listing
- `/api/v1/projects` - Project management
- `/api/v1/tasks` - Task management (global)
- `/api/v1/projects/{project}/tasks` - Project-specific tasks
- `/api/v1/dashboard` - Dashboard analytics

#### Utility Endpoints
- `GET /api/v1/health` - Health check

### Postman Collection

Import the Postman collection for easy API testing:
- File: `KoldaTech Backend API.postman_collection.json`

---

## Testing

### Test Structure

```
tests/
├── Feature/
│   ├── Architecture/
│   │   ├── ArchTest.php
│   │   └── GlobalArchTest.php
│   └── Http/
│       ├── AuthenticationTest.php
│       ├── ProjectAuthorizationTest.php
│       ├── ProjectTest.php
│       ├── TaskAuthorizationTest.php
│       ├── TaskTest.php
│       └── UserTest.php
├── Unit/
├── Pest.php
└── TestCase.php
```

### Running Tests

```bash
# Run all tests
docker-compose exec app php artisan test

# Run specific test file
docker-compose exec app php artisan test tests/Feature/Http/AuthenticationTest.php

### Test Coverage

Current test coverage includes:
- ✅ Authentication (login, logout, refresh, profile)
- ✅ Project CRUD operations
- ✅ Project authorization policies
- ✅ Task CRUD operations
- ✅ Task authorization policies
- ✅ User management
- ✅ Architecture tests (layering, naming conventions)

#### Naming Conventions
- **Controllers**: `{Resource}Controller` (e.g., `UserController`)
- **Models**: Singular, PascalCase (e.g., `User`, `Project`)
- **Tables**: Plural, snake_case (e.g., `users`, `projects`)
- **Migrations**: `{action}_{table}_table` (e.g., `create_users_table`)
- **Policies**: `{Model}Policy` (e.g., `UserPolicy`)
- **Resources**: `{Model}Resource` (e.g., `UserResource`)
- **Requests**: `{Action}{Model}Request` (e.g., `StoreUserRequest`)
- **DTOs**: `{Model}DTO` (e.g., `UserDTO`)
- **Services**: `{Purpose}Service` (e.g., `AuthenticationService`)
- **Filters**: `{Model}Filter` (e.g., `UserFilter`)


### Commit Message Convention

Follow conventional commits:
- `feat:` - New feature
- `fix:` - Bug fix
- `docs:` - Documentation changes
- `style:` - Code style changes (formatting)
- `refactor:` - Code refactoring
- `test:` - Adding or updating tests
- `chore:` - Maintenance tasks

# Clear caches
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear
```

---

### Repository
- **GitHub**: https://github.com/S4F4Y4T/koldaTaskBackend

**Last Updated**: December 2, 2024  
**Version**: 1.0.0  
**Maintained by**: S4F4Y4T
