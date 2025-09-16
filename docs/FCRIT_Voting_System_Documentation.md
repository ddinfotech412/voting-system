# FCRIT Voting System - Comprehensive Documentation

## Table of Contents
1. [Project Overview](#project-overview)
2. [System Architecture](#system-architecture)
3. [Features and Functionality](#features-and-functionality)
4. [User Roles and Access](#user-roles-and-access)
5. [Database Schema](#database-schema)
6. [Installation and Setup](#installation-and-setup)
7. [User Interface Guide](#user-interface-guide)
8. [Security Features](#security-features)
9. [Error Handling and Logging](#error-handling-and-logging)
10. [Screenshots and Visual Guide](#screenshots-and-visual-guide)
11. [Technical Specifications](#technical-specifications)
12. [Future Enhancements](#future-enhancements)

---

## Project Overview

The FCRIT Voting System is a comprehensive web-based election management platform designed specifically for Fr. Conceicao Rodrigues Institute of Technology (FCRIT). The system facilitates student council elections with a modern, secure, and user-friendly interface.

### Key Highlights
- **Modern Web Technology**: Built with PHP, MySQL, and Bootstrap
- **Responsive Design**: Works seamlessly across all devices
- **Secure Voting**: Advanced security measures ensure vote integrity
- **Real-time Analytics**: Comprehensive reporting and data visualization
- **Multi-role Support**: Separate interfaces for voters, candidates, and administrators

---

## System Architecture

### Technology Stack
- **Backend**: PHP 7.4+
- **Database**: MySQL/MariaDB
- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 5.3.2
- **Icons**: Font Awesome 6.0.0
- **Charts**: Chart.js for data visualization

### System Components
```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   User Layer    │    │  Application    │    │   Database      │
│                 │    │     Layer       │    │     Layer       │
│ • Landing Page  │◄──►│ • Authentication│◄──►│ • login         │
│ • Login/Register│    │ • Vote Actions  │    │ • candidates    │
│ • User Dashboard│    │ • Admin Panel   │    │ • campaign      │
│ • Admin Panel   │    │ • Error Logger  │    │ • votes         │
└─────────────────┘    └─────────────────┘    └─────────────────┘
```

---

## Features and Functionality

### 1. User Registration and Authentication
- **Secure Registration**: Password hashing with PHP's `password_hash()`
- **User Validation**: Comprehensive form validation
- **Department Support**: Support for 5 departments (Computer, IT, Mechanical, Electrical, EXTC)
- **Academic Year Tracking**: Year-wise user categorization
- **Duplicate Prevention**: Unique user ID validation

### 2. Candidate Management
- **Application System**: Complete candidate application process
- **Document Upload**: Support for profile pictures and certificates
- **Position Categories**: 4 leadership positions available
  - General Secretary
  - Joint Secretary
  - Sports Secretary
  - Cultural Secretary
- **Application Review**: Admin approval/rejection system
- **Candidate Profiles**: Detailed candidate information display

### 3. Voting System
- **Secure Voting**: One vote per user per election
- **Real-time Validation**: Prevents duplicate voting
- **Election Status Control**: Admin-controlled voting periods
- **Vote Confirmation**: Modal-based vote confirmation
- **Vote History**: Complete audit trail of all votes

### 4. Administrative Dashboard
- **Comprehensive Analytics**: Real-time election statistics
- **User Management**: Add, edit, and manage user accounts
- **Candidate Management**: Review and approve applications
- **Election Control**: Start, stop, and declare results
- **Vote History**: Complete voting records
- **Error Monitoring**: System error tracking and logging

### 5. Analytics and Reporting
- **Voter Turnout**: Real-time participation statistics
- **Position-wise Results**: Detailed breakdown by position
- **Department Analysis**: Department-wise participation
- **Top Performers**: Leading candidates tracking
- **Interactive Charts**: Visual data representation using Chart.js

### 6. Security Features
- **Session Management**: Secure user sessions
- **SQL Injection Prevention**: Prepared statements
- **XSS Protection**: Input sanitization
- **Access Control**: Role-based permissions
- **Error Logging**: Comprehensive audit trail

---

## User Roles and Access

### 1. Voters
**Access Level**: Basic
**Capabilities**:
- Register and login
- View candidate profiles
- Cast votes (once per election)
- View election results (when declared)

### 2. Candidates
**Access Level**: Enhanced
**Capabilities**:
- All voter capabilities
- Submit nomination applications
- Upload campaign materials
- View application status
- Edit profile information

### 3. Administrators
**Access Level**: Full
**Capabilities**:
- All user capabilities
- Manage user accounts
- Review candidate applications
- Control election status
- View comprehensive analytics
- Access error logs
- Declare results

---

## Database Schema

### Core Tables

#### 1. `login` Table
```sql
- sr (int, Primary Key, Auto Increment)
- id (varchar(8), Unique)
- uname (varchar(255))
- pw (varchar(255), Hashed)
- voteStatus (int, 0=Not Voted, 1=Voted, 2=Election Ended, 3=Results Declared)
- department (varchar(255))
- year (varchar(10))
```

#### 2. `candidates` Table
```sql
- id (varchar(8), Primary Key)
- name (varchar(255))
- pfp (varchar(255), Profile Picture Path)
- dept (enum: 5 departments)
- post (enum: 4 positions)
- reason (text, Application Reason)
- cgpa (decimal(5,3))
- achieve (text, Achievements)
- club (text, Club Participation)
- cert (varchar(255), Certificate Path)
- detail (text, Additional Details)
- status (varchar(20), Pending/Accepted/Rejected)
- comments (text, Admin Comments)
- attempts (int(1))
- voteCount (int(5), Default 0)
```

#### 3. `campaign` Table
```sql
- id (varchar(8), Primary Key)
- motto (text)
- size (varchar(48))
- campaign (varchar(255), Campaign Image Path)
```

#### 4. `votes` Table
```sql
- voter_id (varchar(8))
- voter_name (varchar(255))
- candidate_id (varchar(8))
- candidate_name (varchar(255))
- position (varchar(255))
- timestamp (datetime)
```

---

## Installation and Setup

### Prerequisites
- PHP 7.4 or later
- MySQL 5.7+ or MariaDB 10.3+
- Web server (Apache/Nginx)
- Composer (optional)

### Installation Steps

1. **Clone the Repository**
   ```bash
   git clone [repository-url]
   cd voting-system
   ```

2. **Database Setup**
   ```sql
   CREATE DATABASE voting_system;
   USE voting_system;
   SOURCE voting_system.sql;
   ```

3. **Configuration**
   - Update database credentials in `common/connect.php`
   - Set proper file permissions for uploads directory
   - Configure web server document root

4. **File Permissions**
   ```bash
   chmod 755 assets/
   chmod 755 logs/
   chmod 755 uploads/
   ```

5. **Access the System**
   - Navigate to `http://your-domain/`
   - Register as admin or regular user
   - Begin election setup

---

## User Interface Guide

### Landing Page
- **Modern Design**: Clean, professional interface
- **Feature Highlights**: Key system capabilities
- **Navigation**: Easy access to login/register
- **Responsive**: Mobile-friendly design

### Login/Registration
- **Secure Forms**: Validated input fields
- **Error Handling**: Clear error messages
- **Success Feedback**: Confirmation messages
- **Password Requirements**: Minimum 6 characters

### User Dashboard
- **Role-based Interface**: Different views for different users
- **Quick Actions**: Easy access to main functions
- **Status Indicators**: Clear election status display
- **Navigation**: Intuitive menu system

### Admin Panel
- **Comprehensive Dashboard**: Overview of all system metrics
- **Sidebar Navigation**: Easy access to all admin functions
- **Real-time Updates**: Live data refresh
- **Modal Dialogs**: Confirmation for critical actions

---

## Security Features

### Authentication Security
- **Password Hashing**: PHP `password_hash()` with default algorithm
- **Session Management**: Secure session handling
- **Input Validation**: Server-side validation for all inputs
- **SQL Injection Prevention**: Prepared statements throughout

### Access Control
- **Role-based Access**: Different permission levels
- **Session Validation**: Continuous session verification
- **Admin-only Functions**: Protected administrative features
- **Vote Protection**: One vote per user enforcement

### Data Protection
- **File Upload Security**: Validated file types and sizes
- **XSS Prevention**: Input sanitization
- **CSRF Protection**: Form token validation
- **Error Information**: Limited error exposure

---

## Error Handling and Logging

### Error Logger Class
The system includes a comprehensive error logging system (`common/errorLogger.php`):

#### Features
- **Multiple Error Types**: ERROR, WARNING, NOTICE, FATAL, CUSTOM
- **Context Information**: IP address, user agent, request URI
- **Stack Traces**: Detailed error tracking
- **Log Rotation**: Automatic log file management
- **Database Error Logging**: Special handling for database errors

#### Error Categories
- **Authentication Errors**: Login/registration issues
- **Database Errors**: Query failures and connection issues
- **Voting Errors**: Vote-related problems
- **System Errors**: General application errors

#### Log Management
- **Automatic Rotation**: When log files exceed 10MB
- **Retention Policy**: Keeps 5 historical log files
- **Admin Access**: View and clear logs through admin panel
- **Real-time Monitoring**: Live error tracking

---

## Screenshots and Visual Guide

### System Screenshots
The system includes comprehensive visual documentation:

#### User Interface
- **Login Page**: Clean, modern login interface
- **Registration Form**: Step-by-step user registration
- **User Dashboard**: Role-based dashboard design
- **Voting Interface**: Intuitive voting experience

#### Admin Interface
- **Admin Dashboard**: Comprehensive overview panel
- **Candidate Management**: Application review interface
- **Analytics Dashboard**: Real-time charts and statistics
- **User Management**: Account administration tools

#### Mobile Responsiveness
- **Mobile Login**: Optimized for mobile devices
- **Responsive Tables**: Adaptive data display
- **Touch-friendly**: Mobile-optimized interactions

---

## Technical Specifications

### Performance
- **Database Optimization**: Indexed queries for fast retrieval
- **Image Optimization**: Compressed profile pictures and certificates
- **Caching**: Session-based data caching
- **Responsive Design**: Optimized for all screen sizes

### Browser Compatibility
- **Modern Browsers**: Chrome, Firefox, Safari, Edge
- **Mobile Browsers**: iOS Safari, Chrome Mobile
- **JavaScript**: ES6+ features with fallbacks
- **CSS**: Bootstrap 5.3.2 compatibility

### File Structure
```
voting-system/
├── admin/                 # Admin panel files
├── assets/               # Static assets (images, CSS)
├── common/               # Shared PHP files
├── docs/                 # Documentation
├── logs/                 # Error logs
├── screenshots/          # System screenshots
├── users/                # User interface files
├── voting_system.sql     # Database schema
└── index.php            # Entry point
```

---

## Future Enhancements

### Planned Features
1. **Email Notifications**: Automated email alerts
2. **Advanced Analytics**: More detailed reporting
3. **Mobile App**: Native mobile application
4. **Multi-language Support**: Internationalization
5. **API Integration**: RESTful API for external access
6. **Advanced Security**: Two-factor authentication
7. **Audit Trail**: Enhanced logging and tracking
8. **Backup System**: Automated database backups

### Scalability Improvements
1. **Load Balancing**: Support for multiple servers
2. **Database Optimization**: Query performance improvements
3. **Caching Layer**: Redis/Memcached integration
4. **CDN Support**: Content delivery network integration

---

## Conclusion

The FCRIT Voting System represents a modern, secure, and comprehensive solution for student council elections. With its robust feature set, intuitive interface, and strong security measures, it provides an excellent platform for democratic participation within the academic community.

The system's modular architecture allows for easy maintenance and future enhancements, while its responsive design ensures accessibility across all devices. The comprehensive error logging and analytics features provide administrators with the tools needed to manage elections effectively and transparently.

---

*This documentation is maintained and updated regularly to reflect the current state of the FCRIT Voting System. For technical support or feature requests, please contact the development team.*
