# Smart Presence API Documentation

## Table of Contents
1. [Authentication](#authentication)
2. [User Management](#user-management)
3. [Face Recognition](#face-recognition)
4. [Attendance](#attendance)
5. [Schedules](#schedules)
6. [Classrooms](#classrooms)
7. [Reports](#reports)
8. [Teacher Dashboard](#teacher-dashboard)
9. [Admin Dashboard](#admin-dashboard)

## Authentication

### Login
```
POST /api/login
```

**Request Body:**
```json
{
  "email": "user@example.com",
  "password": "password"
}
```

**Response:**
```json
{
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "user@example.com"
  },
  "token": "1|abcdefghijklmnopqrstuvwxyz"
}
```

### Register
```
POST /api/register
```

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "user@example.com",
  "password": "password",
  "password_confirmation": "password"
}
```

**Response:**
```json
{
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "user@example.com"
  },
  "token": "1|abcdefghijklmnopqrstuvwxyz"
}
```

### Logout
```
POST /api/logout
```

**Headers:**
```
Authorization: Bearer <token>
```

**Response:**
```json
{
  "message": "Logged out successfully"
}
```

## User Management

### Get All Users
```
GET /api/users
```

**Headers:**
```
Authorization: Bearer <token>
```

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "John Doe",
      "email": "user@example.com"
    }
  ],
  "links": {},
  "meta": {}
}
```

### Get User by ID
```
GET /api/users/{id}
```

**Headers:**
```
Authorization: Bearer <token>
```

### Create User
```
POST /api/users
```

**Headers:**
```
Authorization: Bearer <token>
```

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "user@example.com",
  "password": "password"
}
```

### Update User
```
PUT /api/users/{id}
```

**Headers:**
```
Authorization: Bearer <token>
```

**Request Body:**
```json
{
  "name": "John Doe Updated",
  "email": "updated@example.com"
}
```

### Delete User
```
DELETE /api/users/{id}
```

**Headers:**
```
Authorization: Bearer <token>
```

## Face Recognition

### Upload Face Photo
```
POST /api/face/upload
```

**Headers:**
```
Authorization: Bearer <token>
Content-Type: multipart/form-data
```

**Form Data:**
```
photo: [image file]
user_id: 1
```

**Response:**
```json
{
  "message": "Photo uploaded successfully",
  "path": "faces/photo.jpg"
}
```

### Get All Face Embeddings
```
GET /api/face-embeddings
```

**Headers:**
```
Authorization: Bearer <token>
```

### Get Face Embedding by ID
```
GET /api/face-embeddings/{id}
```

**Headers:**
```
Authorization: Bearer <token>
```

### Create Face Embedding
```
POST /api/face-embeddings
```

**Headers:**
```
Authorization: Bearer <token>
```

### Update Face Embedding
```
PUT /api/face-embeddings/{id}
```

**Headers:**
```
Authorization: Bearer <token>
```

### Delete Face Embedding
```
DELETE /api/face-embeddings/{id}
```

**Headers:**
```
Authorization: Bearer <token>
```

## Attendance

### Get All Attendances
```
GET /api/attendances
```

**Headers:**
```
Authorization: Bearer <token>
```

### Get Attendance by ID
```
GET /api/attendances/{id}
```

**Headers:**
```
Authorization: Bearer <token>
```

### Create Attendance
```
POST /api/attendances
```

**Headers:**
```
Authorization: Bearer <token>
```

### Update Attendance
```
PUT /api/attendances/{id}
```

**Headers:**
```
Authorization: Bearer <token>
```

### Delete Attendance
```
DELETE /api/attendances/{id}
```

**Headers:**
```
Authorization: Bearer <token>
```

### Process Facial Recognition
```
POST /api/attendance/process
```

**Headers:**
```
Authorization: Bearer <token>
```

**Request Body:**
```json
{
  "user_id": 1,
  "confidence_score": 0.95,
  "raw_response": {}
}
```

**Response:**
```json
{
  "message": "Recognition data received and queued for processing"
}
```

## Schedules

### Get All Schedules
```
GET /api/schedules
```

**Headers:**
```
Authorization: Bearer <token>
```

### Get Schedule by ID
```
GET /api/schedules/{id}
```

**Headers:**
```
Authorization: Bearer <token>
```

### Create Schedule
```
POST /api/schedules
```

**Headers:**
```
Authorization: Bearer <token>
```

### Update Schedule
```
PUT /api/schedules/{id}
```

**Headers:**
```
Authorization: Bearer <token>
```

### Delete Schedule
```
DELETE /api/schedules/{id}
```

**Headers:**
```
Authorization: Bearer <token>
```

## Classrooms

### Get All Classrooms
```
GET /api/classrooms
```

**Headers:**
```
Authorization: Bearer <token>
```

### Get Classroom by ID
```
GET /api/classrooms/{id}
```

**Headers:**
```
Authorization: Bearer <token>
```

### Create Classroom
```
POST /api/classrooms
```

**Headers:**
```
Authorization: Bearer <token>
```

### Update Classroom
```
PUT /api/classrooms/{id}
```

**Headers:**
```
Authorization: Bearer <token>
```

### Delete Classroom
```
DELETE /api/classrooms/{id}
```

**Headers:**
```
Authorization: Bearer <token>
```

## Reports

### Daily Attendance Report
```
GET /api/reports/daily
```

**Headers:**
```
Authorization: Bearer <token>
```

**Query Parameters:**
```
date: 2025-12-01 (optional, defaults to today)
```

### Monthly Recap Report
```
GET /api/reports/monthly
```

**Headers:**
```
Authorization: Bearer <token>
```

**Query Parameters:**
```
month: 12 (optional, defaults to current month)
year: 2025 (optional, defaults to current year)
```

### Late Arrivals Report
```
GET /api/reports/late
```

**Headers:**
```
Authorization: Bearer <token>
```

**Query Parameters:**
```
start_date: 2025-12-01 (optional, defaults to start of month)
end_date: 2025-12-31 (optional, defaults to end of month)
```

## Teacher Dashboard

### Teacher Dashboard Statistics
```
GET /api/teacher/dashboard
```

**Headers:**
```
Authorization: Bearer <token>
```

### Get Students in Teacher's Classrooms
```
GET /api/teacher/students
```

**Headers:**
```
Authorization: Bearer <token>
```

### Get Student Attendance Records
```
GET /api/teacher/student-attendance/{studentId}
```

**Headers:**
```
Authorization: Bearer <token>
```

### Create Leave Request for Student
```
POST /api/teacher/leave-request/{studentId}
```

**Headers:**
```
Authorization: Bearer <token>
```

## Admin Dashboard

### Admin Dashboard Statistics
```
GET /api/admin/dashboard
```

**Headers:**
```
Authorization: Bearer <token>
```

### Get All Users (Admin)
```
GET /api/admin/users
```

**Headers:**
```
Authorization: Bearer <token>
```

### Get All Classrooms (Admin)
```
GET /api/admin/classrooms
```

**Headers:**
```
Authorization: Bearer <token>
```

### Get Attendance Records (Admin)
```
GET /api/admin/attendances
```

**Headers:**
```
Authorization: Bearer <token>
```

**Query Parameters:**
```
status: present|late|absent (optional)
date: 2025-12-01 (optional)
user_id: 1 (optional)
```

### Get System Statistics
```
GET /api/admin/statistics
```

**Headers:**
```
Authorization: Bearer <token>
```

**Query Parameters:**
```
start_date: 2025-12-01 (optional)
end_date: 2025-12-31 (optional)
```