# KoldaTech Backend API

A modern, scalable RESTful API built with Laravel 11 for project and task management with comprehensive authentication, authorization, and role-based access control.

[![PHP Version](https://img.shields.io/badge/PHP-8.3-blue.svg)](https://php.net)
[![Laravel Version](https://img.shields.io/badge/Laravel-11.9-red.svg)](https://laravel.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

## Quick Start

### Prerequisites
- Docker & Docker Compose
- Git

### Installation

```bash
# Clone the repository
git clone https://github.com/S4F4Y4T/koldaTaskBackend.git
cd koldaTaskBackend

# Copy environment file
cp .env.example .env

# For Email Checking, update .env file with mailtrap.io settings

# Start Docker containers
docker-compose up -d --build

# Install dependencies and setup
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan jwt:secret
docker-compose exec app php artisan migrate:fresh --seed

# Set permissions
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

### Access Points

- **API**: http://127.0.0.1:6000/api/v1
- **phpMyAdmin**: http://127.0.0.1:6080
- **Health Check**: http://127.0.0.1:6000/api/v1/health

### Default Login

```json
{
  "email": "admin@example.com",
  "password": "password"
}
```

## Documentation

- **[Technical Documentation](TECHNICAL_DOCUMENTATION.md)** - Complete project architecture, features, and development guide
- **[API Documentation](api_documentation.md)** - Comprehensive API endpoint reference
- **[Postman Collection](KoldaTech%20Backend%20API.postman_collection.json)** - Ready-to-use API collection

## Key Features

### Core Functionality
-  **JWT Authentication** - Secure token-based authentication with refresh tokens
-  **User Management** - Complete CRUD with role assignment
-  **RBAC System** - Role-based access control with polymorphic permissions
-  **Project Management** - Track projects with status, dates, and completion metrics
-  **Task Management** - Assign tasks with priorities, deadlines, and notifications
-  **Dashboard Analytics** - Real-time statistics and insights
-  **Email Notifications** - Automated task assignment emails

### Technical Features
-  **Service Pattern** - Clean separation of business logic
-  **DTO Pattern** - Type-safe data transfer
-  **Advanced Filtering** - Dynamic query filtering and sorting
-  **Pest PHP Testing** - Comprehensive test coverage
-  **Docker Ready** - Full containerization with Docker Compose
-  **API Versioning** - Version-controlled API endpoints
-  **Policy-Based Authorization** - Resource-level access control

## Technology Stack

- **Framework**: Laravel 11.9
- **PHP**: 8.3
- **Database**: MySQL 8.0
- **Authentication**: JWT (tymon/jwt-auth)
- **Testing**: Pest PHP 3.7
- **Containerization**: Docker & Docker Compose
- **Web Server**: Nginx
- **Process Manager**: PHP-FPM

## API Endpoints

### Authentication
- `POST /api/v1/login` - User login
- `POST /api/v1/refresh` - Refresh token
- `POST /api/v1/logout` - User logout
- `GET /api/v1/me` - Get authenticated user

### Resources
- `/api/v1/users` - User management
- `/api/v1/roles` - Role management
- `/api/v1/modules` - Modules & permissions
- `/api/v1/projects` - Project management
- `/api/v1/tasks` - Task management
- `/api/v1/dashboard` - Dashboard analytics

See [API Documentation](api_documentation.md) for complete endpoint details.

## Testing

```bash
# Run all tests
docker-compose exec app php artisan test

# Run specific test file
docker-compose exec app php artisan test tests/Feature/Http/AuthenticationTest.php
```

## Project Structure

```
app/
├── Builders/          # Custom query builders
├── DTOs/              # Data Transfer Objects
├── Enums/             # Enumeration classes
├── Events/            # Event classes
├── Filters/           # Query filters
├── Http/
│   ├── Controllers/   # API controllers
│   ├── Middleware/    # Custom middleware
│   ├── Requests/      # Form request validation
│   └── Resources/     # API resources
├── Jobs/              # Queued jobs
├── Listeners/         # Event listeners
├── Mail/              # Mailable classes
├── Models/            # Eloquent models
├── Policies/          # Authorization policies
├── Services/          # Business logic services
└── Traits/            # Reusable traits
```

## Docker Services

- **app** - PHP-FPM 8.3 application container
- **nginx** - Nginx web server (port 6000)
- **db** - MySQL 8.0 database
- **phpmyadmin** - Database management GUI (port 6080)

## Author

**S4F4Y4T**

- GitHub: [@S4F4Y4T](https://github.com/S4F4Y4T)
- Repository: [koldaTaskBackend](https://github.com/S4F4Y4T/koldaTaskBackend)

---

For detailed technical documentation, architecture details, and development guidelines, see [TECHNICAL_DOCUMENTATION.md](TECHNICAL_DOCUMENTATION.md).
