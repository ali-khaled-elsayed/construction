# Construction Marketplace API

A production-grade Laravel 11 API for a construction/service marketplace application.

## 🏗️ Project Overview

This is a comprehensive marketplace platform connecting customers with service providers for construction and renovation projects. The system supports three user roles: Customer, Service Provider, and Admin.

## 🚀 Tech Stack

- **Backend**: Laravel 11
- **Authentication**: Laravel Sanctum
- **Architecture**: Service-Repository Pattern
- **Database**: MySQL (migrations included)
- **Multi-language**: Built-in translation system
- **API**: RESTful with JSON responses

## 📁 Project Structure

```
app/
├── Enums/                          # Type-safe enums
│   ├── UserRole.php               # customer, service_provider, admin
│   ├── JobType.php                # full, partial
│   ├── ServiceType.php            # specialist, service_provider
│   ├── DescriptionType.php        # basic, detailed
│   ├── JobStatus.php              # open, in_progress, completed, cancelled
│   ├── JobSize.php                # small, medium, large
│   ├── Urgency.php                # standard, urgent
│   ├── ShortListingStatus.php     # interested, shortlisted, paid, withdraw, cancelled, accepted
│   └── DataType.php               # text, number, boolean, select
│
├── Models/                         # Eloquent models (25+ models)
│   ├── User.php
│   ├── ServiceProviderProfile.php
│   ├── Job.php
│   ├── JobRequest.php
│   ├── Room.php
│   ├── RoomType.php
│   ├── JobCategory.php
│   ├── Country.php
│   ├── City.php
│   ├── Comment.php
│   ├── Rating.php
│   ├── Gallery.php
│   ├── JobAttribute.php
│   └── [15+ translation and pivot models]
│
├── Modules/                        # Modular architecture
│   ├── Auth/                       # Authentication module
│   │   ├── Controllers/
│   │   │   └── AuthController.php
│   │   ├── Services/
│   │   │   └── AuthService.php
│   │   ├── Repositories/
│   │   │   ├── Contracts/
│   │   │   │   └── AuthRepositoryInterface.php
│   │   │   └── Eloquent/
│   │   │       └── AuthRepository.php
│   │   ├── Requests/
│   │   │   ├── RegisterRequest.php
│   │   │   └── LoginRequest.php
│   │   └── Resources/
│   │       ├── UserResource.php
│   │       ├── ServiceProviderProfileResource.php
│   │       ├── CityResource.php
│   │       └── CountryResource.php
│   ├── User/                       # User management (TODO)
│   ├── Job/                        # Job management (TODO)
│   ├── Provider/                   # Provider management (TODO)
│   ├── Location/                   # Location management (TODO)
│   ├── Translation/                # Translation management (TODO)
│   └── Admin/                      # Admin panel (TODO)
│
├── Repositories/                   # Base repository pattern
│   ├── Contracts/
│   │   └── BaseRepositoryInterface.php
│   └── Eloquent/
│       └── BaseRepository.php
│
├── Services/                       # Base service layer
│   └── BaseService.php
│
├── Helpers/                        # Global helpers
├── Traits/                         # Reusable traits
└── DTOs/                          # Data transfer objects
```

## 🗄️ Database Schema

The application uses 25+ tables including:

### Core Tables
- `users` - User accounts with role-based access
- `service_provider_profiles` - Provider-specific data
- `job_requests` - Customer job requests
- `jobs` - Individual job items
- `rooms` - Room details for detailed job descriptions
- `job_categories` - Service categories
- `job_attributes` - Dynamic job attributes

### Translation Tables (Multi-language Support)
- `languages` - Supported languages
- `country_translations`, `city_translations`
- `room_type_translations`, `job_category_translations`
- `job_attribute_translations`, `job_attribute_option_translations`

### Relationship Tables
- `job_short_listing` - Provider-job assignments
- `job_history` - Job status change tracking
- `job_short_listing_history` - Assignment status tracking
- `comments` - Discussion threads
- `ratings` - User ratings and reviews
- `galleries` - Provider portfolio images

## 🔐 Authentication

The API uses Laravel Sanctum for token-based authentication.

### Endpoints

#### Public Routes
- `POST /api/auth/register` - Register new user
- `POST /api/auth/login` - Login user

#### Protected Routes (require `Authorization: Bearer {token}`)
- `POST /api/auth/logout` - Logout user
- `GET /api/auth/me` - Get current user
- `GET /api/auth/tokens` - Get user tokens
- `DELETE /api/auth/tokens/{tokenId}` - Revoke specific token

### Registration Payload

```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "customer", // or "service_provider"
  "bio": "Professional contractor", // optional, for providers
  "city_id": 1, // optional
  "country_code": "US" // optional, ISO 3166-1 alpha-2
}
```

## 🎯 Key Features Implemented

### ✅ Completed (Phase 1)
1. **Modular Architecture** - Clean separation of concerns
2. **Service-Repository Pattern** - Abstracted data access
3. **Type-Safe Enums** - No more magic strings
4. **Multi-language Support** - Built-in translation system
5. **Comprehensive Models** - 25+ models with relationships
6. **Authentication System** - Register, login, logout with Sanctum
7. **API Resources** - Consistent JSON responses
8. **Form Requests** - Request validation
9. **Database Migrations** - Complete schema with foreign keys
10. **History Tracking** - Audit trails for status changes

### 🚧 Next Steps (Phase 2)
1. User management module
2. Job creation and management
3. Provider search and filtering
4. Advanced filtering system
5. Admin dashboard with Filament
6. Policies and permissions
7. Notifications system
8. File upload handling

### 📱 Future Phases
- **Phase 3**: Next.js frontend
- **Phase 4**: React Native mobile app

## 🛠️ Setup Instructions

### Prerequisites
- PHP 8.2+
- Composer
- MySQL 8.0+ or MariaDB
- Node.js (for frontend)

### Installation

1. Clone the repository
```bash
git clone <repository-url>
cd construction-marketplace-api
```

2. Install dependencies
```bash
composer install
```

3. Copy environment file
```bash
cp .env.example .env
```

4. Configure database in `.env`
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=construction_marketplace
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. Generate application key
```bash
php artisan key:generate
```

6. Run migrations
```bash
php artisan migrate
```

7. Start development server
```bash
php artisan serve
```

The API will be available at `http://localhost:8000`

### Testing Authentication

```bash
# Register a new user
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "role": "customer"
  }'

# Login
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'

# Access protected route
curl -X GET http://localhost:8000/api/auth/me \
  -H "Authorization: Bearer {token}"
```

## 📊 API Response Format

All API responses follow a consistent format:

### Success Response
```json
{
  "message": "Operation successful",
  "data": { /* resource data */ },
  "token": "optional_token"
}
```

### Error Response
```json
{
  "message": "Error description",
  "errors": { /* validation errors */ }
}
```

### Paginated Response
```json
{
  "data": [ /* items */ ],
  "total": 100,
  "per_page": 15,
  "current_page": 1,
  "last_page": 7,
  "from": 1,
  "to": 15,
  "links": {
    "first": "url",
    "last": "url",
    "prev": "url",
    "next": "url"
  }
}
```

## 🤝 Contributing

This is an enterprise-grade project. Please follow these guidelines:

1. Follow the existing architecture patterns
2. Write tests for new features
3. Use type hints and proper documentation
4. Keep modules independent
5. Maintain backward compatibility

## 📄 License

This project is proprietary software. All rights reserved.

## 📞 Support

For questions or issues, please contact the development team.

---

**Built with ❤️ using Laravel 11**
