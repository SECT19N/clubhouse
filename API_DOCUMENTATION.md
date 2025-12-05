# Club Management System API Documentation

## Table of Contents
1. [Overview](#overview)
2. [Authentication](#authentication)
3. [API Endpoints](#api-endpoints)
4. [Query Parameters](#query-parameters)
5. [Error Handling](#error-handling)
6. [Testing](#testing)
7. [Backup](#backup)

---

## Overview

The Club Management System API is a RESTful API built with Laravel 12 and Laravel Sanctum for authentication. It provides endpoints for managing clubs, students, events, and their relationships.

**Base URL:** `http://localhost:8000/api`

**Authentication:** Bearer Token (Laravel Sanctum)

---

## Authentication

### Register
Create a new user account.

**Endpoint:** `POST /api/register`

**Request Body:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "role": "student",
    "student_id": null
}
```

**Response:** `201 Created`
```json
{
    "message": "User registered successfully",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "role": "student"
    },
    "access_token": "1|xxxxxxxxxxxxx",
    "token_type": "Bearer"
}
```

### Login
Authenticate and receive an access token.

**Endpoint:** `POST /api/login`

**Request Body:**
```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

**Response:** `200 OK`
```json
{
    "message": "Login successful",
    "user": {...},
    "access_token": "1|xxxxxxxxxxxxx",
    "token_type": "Bearer"
}
```

### Get Current User
Get authenticated user information.

**Endpoint:** `GET /api/user`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:** `200 OK`

### Logout
Revoke the current access token.

**Endpoint:** `POST /api/logout`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:** `200 OK`

---

## API Endpoints

### Clubs

#### Get All Clubs
**Endpoint:** `GET /api/clubs`

**Query Parameters:**
- `search` - Search by club name
- `founded_year` - Filter by founded year
- `room` - Filter by room
- `sort_by` - Sort field (name, founded_year, room, created_at)
- `sort_order` - Sort order (asc, desc)
- `per_page` - Items per page (default: 15)
- `with_trashed` - Include soft-deleted items
- `only_trashed` - Show only soft-deleted items

**Example:**
```
GET /api/clubs?search=chess&founded_year=2020&sort_by=name&per_page=15
```

#### Get Club by ID
**Endpoint:** `GET /api/clubs/{id}`

#### Create Club
**Endpoint:** `POST /api/clubs`

**Request Body:**
```json
{
    "name": "Chess Club",
    "room": "A101",
    "founded_year": 2020,
    "president_email": "president@chessclub.edu"
}
```

#### Update Club
**Endpoint:** `PUT /api/clubs/{id}` or `PATCH /api/clubs/{id}`

#### Delete Club (Soft Delete)
**Endpoint:** `DELETE /api/clubs/{id}`

#### Restore Club
**Endpoint:** `POST /api/clubs/{id}/restore`

#### Get Club Students
**Endpoint:** `GET /api/clubs/{id}/students`

**Response:**
```json
{
    "club_id": 1,
    "club_name": "Chess Club",
    "students": [...],
    "total_students": 5
}
```

#### Add Student to Club
**Endpoint:** `POST /api/clubs/{id}/students`

**Request Body:**
```json
{
    "student_id": 1,
    "role": "member"
}
```

**Roles:** `member`, `treasurer`, `president`, `secretary`

#### Remove Student from Club
**Endpoint:** `DELETE /api/clubs/{id}/students/{student_id}`

#### Update Student Role in Club
**Endpoint:** `PUT /api/clubs/{id}/students/{student_id}/role`

**Request Body:**
```json
{
    "role": "treasurer"
}
```

---

### Students

#### Get All Students
**Endpoint:** `GET /api/students`

**Query Parameters:**
- `search` - Search by first name, last name, or email
- `graduation_year` - Filter by graduation year
- `gender` - Filter by gender (M, F)
- `gpa_min` - Minimum GPA
- `gpa_max` - Maximum GPA
- `sort_by` - Sort field (first_name, last_name, email, graduation_year, gpa, created_at)
- `sort_order` - Sort order (asc, desc)
- `per_page` - Items per page (default: 10)
- `with_trashed` - Include soft-deleted items
- `only_trashed` - Show only soft-deleted items

**Example:**
```
GET /api/students?search=john&graduation_year=2025&gpa_min=3.5&sort_by=gpa&sort_order=desc
```

#### Get Student by ID
**Endpoint:** `GET /api/students/{id}`

#### Create Student
**Endpoint:** `POST /api/students`

**Request Body:**
```json
{
    "first_name": "John",
    "last_name": "Doe",
    "email": "john.doe@example.com",
    "gender": "M",
    "date_of_birth": "2005-06-15",
    "graduation_year": 2025,
    "gpa": 3.75
}
```

#### Update Student
**Endpoint:** `PUT /api/students/{id}` or `PATCH /api/students/{id}`

#### Delete Student (Soft Delete)
**Endpoint:** `DELETE /api/students/{id}`

#### Restore Student
**Endpoint:** `POST /api/students/{id}/restore`

#### Get Student Clubs
**Endpoint:** `GET /api/students/{id}/clubs`

**Response:**
```json
{
    "student_id": 1,
    "student_name": "John Doe",
    "clubs": [...],
    "total_clubs": 2
}
```

---

### Events

#### Get All Events
**Endpoint:** `GET /api/events`

**Query Parameters:**
- `search` - Search by title, description, or venue
- `club_id` - Filter by club ID
- `start_date` - Filter events from this date (YYYY-MM-DD)
- `end_date` - Filter events until this date (YYYY-MM-DD)
- `venue` - Filter by venue
- `upcoming` - Show only upcoming events (true/false)
- `past` - Show only past events (true/false)
- `sort_by` - Sort field (title, start_time, end_time, venue, expected_audience, created_at)
- `sort_order` - Sort order (asc, desc)
- `per_page` - Items per page (default: 10)
- `with_trashed` - Include soft-deleted items
- `only_trashed` - Show only soft-deleted items

**Example:**
```
GET /api/events?club_id=1&start_date=2025-01-01&upcoming=true&sort_by=start_time
```

#### Get Event by ID
**Endpoint:** `GET /api/events/{id}`

#### Create Event
**Endpoint:** `POST /api/events`

**Request Body:**
```json
{
    "club_id": 1,
    "title": "Chess Tournament",
    "description": "Annual inter-club chess tournament",
    "start_time": "2025-12-15 14:00:00",
    "end_time": "2025-12-15 18:00:00",
    "venue": "Main Hall",
    "expected_audience": 100
}
```

#### Update Event
**Endpoint:** `PUT /api/events/{id}` or `PATCH /api/events/{id}`

#### Delete Event (Soft Delete)
**Endpoint:** `DELETE /api/events/{id}`

#### Restore Event
**Endpoint:** `POST /api/events/{id}/restore`

---

## Query Parameters

### Common Parameters (All Resources)

| Parameter | Type | Description | Example |
|-----------|------|-------------|---------|
| `search` | string | Search term | `?search=chess` |
| `sort_by` | string | Field to sort by | `?sort_by=name` |
| `sort_order` | string | Sort order (asc/desc) | `?sort_order=desc` |
| `per_page` | integer | Items per page | `?per_page=20` |
| `with_trashed` | boolean | Include soft-deleted | `?with_trashed=true` |
| `only_trashed` | boolean | Show only deleted | `?only_trashed=true` |

### Club-Specific Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `founded_year` | integer | Filter by founded year |
| `room` | string | Filter by room |

### Student-Specific Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `graduation_year` | integer | Filter by graduation year |
| `gender` | string | Filter by gender (M/F) |
| `gpa_min` | float | Minimum GPA |
| `gpa_max` | float | Maximum GPA |

### Event-Specific Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `club_id` | integer | Filter by club |
| `start_date` | date | Filter from date (YYYY-MM-DD) |
| `end_date` | date | Filter until date (YYYY-MM-DD) |
| `venue` | string | Filter by venue |
| `upcoming` | boolean | Show only upcoming events |
| `past` | boolean | Show only past events |

---

## Error Handling

### HTTP Status Codes

| Code | Meaning |
|------|---------|
| 200 | Success |
| 201 | Created |
| 204 | No Content (successful delete) |
| 400 | Bad Request |
| 401 | Unauthorized |
| 403 | Forbidden |
| 404 | Not Found |
| 409 | Conflict (e.g., duplicate membership) |
| 422 | Validation Error |
| 500 | Server Error |

### Error Response Format

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": ["The email has already been taken."],
        "password": ["The password must be at least 8 characters."]
    }
}
```

---

## Testing

### Running Tests

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/AuthTest.php

# Run with coverage
php artisan test --coverage
```

### Test Files

- `tests/Feature/AuthTest.php` - Authentication tests
- `tests/Feature/ClubTest.php` - Club CRUD and search tests
- `tests/Feature/StudentTest.php` - Student CRUD and search tests
- `tests/Feature/EventTest.php` - Event CRUD and search tests
- `tests/Feature/ClubStudentRelationshipTest.php` - Relationship management tests

---

## Backup

### Database Backup Command

Create a backup of your database:

```bash
# Default backup location (storage/backups)
php artisan db:backup

# Custom backup path
php artisan db:backup --path=/path/to/backups
```

**Supported Databases:**
- SQLite (copies database file)
- MySQL (uses mysqldump)

**Backup Location:** `storage/backups/backup_{connection}_{timestamp}.{ext}`

**Example:**
```
storage/backups/backup_sqlite_2025-12-05_161548.sqlite
storage/backups/backup_mysql_2025-12-05_161548.sql
```

### Automated Backups

Add to your scheduler (`app/Console/Kernel.php`):

```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('db:backup')->daily();
}
```

---

## Test Users

Default test users (created by seeder):

| Email | Password | Role |
|-------|----------|------|
| admin@clubhouse.com | password123 | admin |
| student@clubhouse.com | password123 | student |
| president@clubhouse.com | password123 | club_president |

---

## Postman Collection

Import the `Club_Management_API.postman_collection.json` file into Postman for easy API testing. The collection includes:

- All endpoints with examples
- Automatic token management
- Pre-configured request bodies
- Query parameter examples

---

## Additional Resources

- **Laravel Documentation:** https://laravel.com/docs
- **Laravel Sanctum:** https://laravel.com/docs/sanctum
- **Postman:** https://www.postman.com/

---

## Support

For issues or questions, please refer to the project README or contact the development team.

