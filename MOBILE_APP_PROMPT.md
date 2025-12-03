# Mobile App Development Prompt for Smart Presence

## Project Overview
Create a Flutter mobile application for the Smart Presence attendance system that integrates seamlessly with the existing Laravel backend API. The app should leverage facial recognition capabilities for attendance tracking and provide role-based access for teachers and administrators.

## Core Features to Implement

### 1. Authentication System
- Login screen with email/password
- Registration capability
- Secure token storage using flutter_secure_storage
- Automatic token refresh
- Logout functionality

### 2. Role-Based Navigation
- Different UI flows for:
  - Teachers
  - Administrators
  - Students (if applicable)
- Dynamic menu based on user roles from backend

### 3. Face Recognition Integration
- Camera integration for face capture
- Image upload to `/api/face/upload` endpoint
- Real-time facial recognition processing
- Confidence score display
- Retry mechanism for low confidence results

### 4. Attendance Management
- Manual attendance recording
- Automated attendance via facial recognition
- Attendance history browsing
- Geolocation tagging for attendance (using device GPS)
- Offline attendance caching with sync capability

### 5. Dashboard Views

#### Teacher Dashboard
- Class overview statistics
- Today's attendance status
- Quick attendance actions
- Student list management
- Leave request approval

#### Admin Dashboard
- System-wide statistics
- User management interface
- Classroom management
- Detailed reporting tools
- System settings access

### 6. Reporting Features
- Daily attendance reports
- Monthly recap views
- Late arrival tracking
- Export capabilities (PDF, Excel)
- Graphical data visualization

### 7. User Management
- Profile editing
- Password change
- Role-specific settings
- Notification preferences

## Technical Requirements

### Architecture
- MVVM (Model-View-ViewModel) pattern
- Provider or Riverpod for state management
- Dio for HTTP client management
- Shared preferences for local data caching
- Proper error handling and loading states

### API Integration Points
All endpoints should integrate with the documented Laravel API:

1. **Authentication Endpoints**
   - POST `/api/login`
   - POST `/api/register`
   - POST `/api/logout`

2. **Face Recognition Endpoints**
   - POST `/api/face/upload`
   - POST `/api/attendance/process`

3. **Attendance Endpoints**
   - GET `/api/attendances`
   - POST `/api/attendances`
   - PUT `/api/attendances/{id}`
   - DELETE `/api/attendances/{id}`

4. **User Management Endpoints**
   - GET `/api/users`
   - POST `/api/users`
   - PUT `/api/users/{id}`
   - DELETE `/api/users/{id}`

5. **Reporting Endpoints**
   - GET `/api/reports/daily`
   - GET `/api/reports/monthly`
   - GET `/api/reports/late`

6. **Teacher-Specific Endpoints**
   - GET `/api/teacher/dashboard`
   - GET `/api/teacher/students`
   - GET `/api/teacher/student-attendance/{studentId}`
   - POST `/api/teacher/leave-request/{studentId}`

7. **Admin-Specific Endpoints**
   - GET `/api/admin/dashboard`
   - GET `/api/admin/users`
   - GET `/api/admin/classrooms`
   - GET `/api/admin/attendances`
   - GET `/api/admin/statistics`

### UI/UX Requirements
- Responsive design for various screen sizes
- Material Design components
- Smooth animations and transitions
- Accessibility compliance
- Dark/light theme support
- Indonesian and English language support

### Security Considerations
- Secure storage of authentication tokens
- Biometric authentication (fingerprint, face unlock)
- Data encryption for sensitive information
- SSL pinning for API communications
- Input validation and sanitization

### Performance Optimization
- Image compression before upload
- Pagination for large data sets
- Caching strategies for offline access
- Background sync for pending operations
- Efficient memory management

## Required Flutter Packages
```yaml
dependencies:
  flutter:
    sdk: flutter
  http: ^0.13.5
  dio: ^4.0.6
  provider: ^6.0.5
  flutter_secure_storage: ^5.0.2
  shared_preferences: ^2.0.15
  camera: ^0.10.0+1
  image_picker: ^0.8.6
  geolocator: ^9.0.2
  flutter_svg: ^1.1.6
  charts_flutter: ^0.12.0
  pdf: ^3.8.1
  permission_handler: ^10.2.0
  local_auth: ^2.1.2
  intl: ^0.17.0
```

## Implementation Phases

### Phase 1: Foundation
- Authentication flow
- Basic navigation
- API service layer
- State management setup

### Phase 2: Core Features
- Face recognition integration
- Attendance recording
- Dashboard implementation
- User profile management

### Phase 3: Advanced Features
- Reporting system
- Offline capabilities
- Push notifications
- Biometric security

### Phase 4: Polish & Optimization
- UI/UX refinement
- Performance optimization
- Testing and bug fixes
- Documentation

## Success Criteria
- Seamless integration with Laravel backend
- Fast and responsive user interface
- Reliable facial recognition processing
- Robust offline functionality
- Comprehensive error handling
- Security best practices implementation
- Cross-platform compatibility (Android & iOS)

## Additional Considerations
- Battery optimization for camera usage
- Network connectivity handling
- Data synchronization strategies
- User onboarding experience
- Help and support integration
- Analytics for usage tracking