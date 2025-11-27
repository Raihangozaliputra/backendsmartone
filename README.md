# Smart Presence (Absen Cerdas) - Backend API

## Overview

Smart Presence is a Laravel-based backend API for a smart attendance system with AI-powered facial recognition capabilities. This system provides RESTful APIs for managing users, attendance, schedules, classrooms, and generating reports.

## Features

### 1. Administrative System & Security
- User management (CRUD operations)
- Role-based access control (RBAC)
- API token authentication with Laravel Sanctum
- Activity logging for audit trails

### 2. AI Facial Recognition & Data Management
- Face embedding storage for facial recognition
- Photo upload functionality for AI training
- Recognition log storage
- Data synchronization with external vector databases

### 3. Attendance & Time Logic
- Class schedule management
- Automated attendance processing
- Late arrival detection
- Manual attendance adjustments
- Geolocation validation

### 4. Teacher/Staff Interface
- Dashboard with class statistics
- Student list management
- Individual student attendance history
- Leave request creation for students

### 5. Reporting & Analytics
- Daily attendance reports
- Monthly recap reports
- Late arrival reports
- Data export capabilities

## Technical Architecture

### Core Components
- **Framework**: Laravel 9.x
- **Authentication**: Laravel Sanctum
- **Authorization**: Spatie Laravel Permission
- **Activity Logging**: Spatie Laravel Activitylog
- **Database**: MySQL
- **Queue System**: Redis/Database
- **Storage**: Local/Public disk

### Key Models
- User (with roles and permissions)
- FaceEmbedding (facial recognition data)
- Attendance (attendance records)
- Schedule (class schedules)
- Classroom (learning spaces)
- Holiday (non-working days)
- LeaveRequest (absence requests)

### API Endpoints
All API endpoints are prefixed with `/api/` and require authentication except for login and registration.

#### Authentication
- `POST /login` - User login
- `POST /register` - User registration
- `POST /logout` - User logout

#### User Management
- `GET /users` - List users
- `POST /users` - Create user
- `GET /users/{id}` - Get user details
- `PUT /users/{id}` - Update user
- `DELETE /users/{id}` - Delete user

#### Face Recognition
- `GET /face-embeddings` - List face embeddings
- `POST /face-embeddings` - Create face embedding
- `GET /face-embeddings/{id}` - Get face embedding
- `PUT /face-embeddings/{id}` - Update face embedding
- `DELETE /face-embeddings/{id}` - Delete face embedding
- `POST /face/upload` - Upload face photo

#### Attendance
- `GET /attendances` - List attendances
- `POST /attendances` - Create attendance
- `GET /attendances/{id}` - Get attendance
- `PUT /attendances/{id}` - Update attendance
- `DELETE /attendances/{id}` - Delete attendance
- `POST /attendance/process` - Process facial recognition

#### Schedules
- `GET /schedules` - List schedules
- `POST /schedules` - Create schedule
- `GET /schedules/{id}` - Get schedule
- `PUT /schedules/{id}` - Update schedule
- `DELETE /schedules/{id}` - Delete schedule

#### Classrooms
- `GET /classrooms` - List classrooms
- `POST /classrooms` - Create classroom
- `GET /classrooms/{id}` - Get classroom
- `PUT /classrooms/{id}` - Update classroom
- `DELETE /classrooms/{id}` - Delete classroom

#### Holidays
- `GET /holidays` - List holidays
- `POST /holidays` - Create holiday
- `GET /holidays/{id}` - Get holiday
- `PUT /holidays/{id}` - Update holiday
- `DELETE /holidays/{id}` - Delete holiday

#### Leave Requests
- `GET /leave-requests` - List leave requests
- `POST /leave-requests` - Create leave request
- `GET /leave-requests/{id}` - Get leave request
- `PUT /leave-requests/{id}` - Update leave request
- `DELETE /leave-requests/{id}` - Delete leave request

#### Reports
- `GET /reports/daily` - Daily attendance report
- `GET /reports/monthly` - Monthly recap report
- `GET /reports/late` - Late arrivals report

#### Teacher-Specific Endpoints
- `GET /teacher/dashboard` - Teacher dashboard statistics
- `GET /teacher/students` - Students in teacher's classrooms
- `GET /teacher/student-attendance/{studentId}` - Attendance history for a student
- `POST /teacher/leave-request/{studentId}` - Create leave request for a student

## Installation

1. Clone the repository
2. Install PHP dependencies: `composer install`
3. Copy `.env.example` to `.env` and configure database settings
4. Generate application key: `php artisan key:generate`
5. Run migrations: `php artisan migrate`
6. Seed the database (optional): `php artisan db:seed`

## Queue Workers

Some operations like processing attendance data are queued for better performance:
- Start queue worker: `php artisan queue:work`

## Scheduled Tasks

The application includes scheduled tasks for maintenance:
- Face embedding cleanup: Runs daily at midnight
- Report generation: Runs monthly on the 1st at 2 AM

To run scheduled tasks, add this cron entry:
```
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## Configuration

Key configuration options can be found in `config/smartpresence.php`:
- Attendance thresholds
- AI service settings
- Storage options
- Notification preferences

## Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Create a pull request

## License

This project is licensed under the MIT License.