# Club Management System API

A comprehensive RESTful API for managing student clubs, students, and events built with Laravel 12 and Laravel Sanctum.

## Features

- ✅ **Authentication & Authorization** - Laravel Sanctum with role-based access control
- ✅ **Full CRUD Operations** - Clubs, Students, Events
- ✅ **Relationship Management** - Club-Student memberships with roles
- ✅ **Search & Filtering** - Advanced querying on all resources
- ✅ **Soft Deletes** - Safe deletion with restore capability
- ✅ **Comprehensive Testing** - Feature tests for all endpoints
- ✅ **Database Backup** - Automated backup command
- ✅ **API Documentation** - Complete documentation (see API_DOCUMENTATION.md)

## Quick Start

### 1. Install Dependencies
```bash
composer install
npm install
```

### 2. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Database Configuration

**For SQLite (default):**
```bash
touch database/database.sqlite
```

**For MySQL:**
Update `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=clubhouse
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 4. Run Migrations & Seeders
```bash
php artisan migrate
php artisan db:seed
```

### 5. Start Development Server
```bash
php artisan serve
```

The API will be available at `http://localhost:8000`

## Testing

### Run Tests
```bash
php artisan test
```

### Test Users
After seeding, you can login with:
- **Admin:** admin@clubhouse.com / password123
- **Student:** student@clubhouse.com / password123
- **President:** president@clubhouse.com / password123

## Database Backup

```bash
# Create backup
php artisan db:backup

# Custom backup path
php artisan db:backup --path=/path/to/backups
```

## API Documentation

See [API_DOCUMENTATION.md](API_DOCUMENTATION.md) for complete API reference.

## Postman Collection

Import `Club_Management_API.postman_collection.json` into Postman for easy testing.

## Project Structure

```
clubhouse/
├── app/
│   ├── Http/
│   │   ├── Controllers/     # API controllers
│   │   ├── Middleware/      # Role-based middleware
│   │   └── Requests/        # Form request validation
│   ├── Models/              # Eloquent models
│   └── Console/
│       └── Commands/        # Backup command
├── database/
│   ├── migrations/          # Database schema
│   ├── seeders/             # Database seeders
│   └── factories/           # Model factories
├── routes/
│   └── api.php              # API routes
├── tests/
│   └── Feature/             # Feature tests
└── storage/
    └── backups/              # Database backups
```

## Technologies

- **Laravel 12** - PHP Framework
- **Laravel Sanctum** - API Authentication
- **SQLite/MySQL** - Database
- **PHPUnit** - Testing Framework

## License

MIT License