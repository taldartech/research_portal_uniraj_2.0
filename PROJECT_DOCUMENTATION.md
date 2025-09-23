# Research Portal - Complete Project Documentation

## Table of Contents
1. [Project Overview](#project-overview)
2. [System Architecture](#system-architecture)
3. [Database Schema](#database-schema)
4. [User Roles & Workflows](#user-roles--workflows)
5. [Features & Modules](#features--modules)
6. [API Endpoints](#api-endpoints)
7. [Installation & Setup](#installation--setup)
8. [Configuration](#configuration)
9. [Deployment](#deployment)
10. [Troubleshooting](#troubleshooting)

---

## Project Overview

### Purpose
The Research Portal is a comprehensive web application designed to manage the entire Ph.D. research lifecycle at the University of Rajasthan. It facilitates scholar registration, thesis submission, approval workflows, and certificate generation.

### Key Features
- **Scholar Registration & Profile Management**
- **Ph.D. Registration Form System**
- **Thesis Submission & Approval Workflow**
- **Certificate Generation System**
- **Multi-level Approval Process**
- **Document Management**
- **Progress Tracking**

### Technology Stack
- **Backend**: Laravel 11.x (PHP 8.2+)
- **Frontend**: Blade Templates, Tailwind CSS, Alpine.js
- **Database**: MySQL 8.0+
- **File Storage**: Laravel Storage (Local/Public)
- **Authentication**: Laravel Breeze

---

## System Architecture

### MVC Pattern
```
app/
├── Http/Controllers/     # Business Logic
├── Models/              # Data Models
├── Views/               # Blade Templates
├── Middleware/          # Request Processing
└── Notifications/       # Email Notifications

resources/
├── views/               # Blade Templates
├── css/                 # Styling
└── js/                  # JavaScript

database/
├── migrations/          # Database Schema
└── seeders/            # Sample Data
```

### User Roles Hierarchy
```
Scholar → Supervisor → HOD → DA → SO → AR → DR → HVC
```

---

## Database Schema

### Core Tables

#### Users Table
```sql
users
├── id (Primary Key)
├── role_id (Foreign Key)
├── name
├── email
├── user_type (scholar, supervisor, hod, da, so, ar, dr, hvc)
├── email_verified_at
└── timestamps
```

#### Scholars Table
```sql
scholars
├── id (Primary Key)
├── user_id (Foreign Key)
├── admission_id (Foreign Key)
├── enrollment_number
├── first_name, last_name
├── date_of_birth, gender
├── contact_number, address
├── research_area
├── enrollment_status
├── registration_form_status
├── [40+ Ph.D. Registration Fields]
└── timestamps
```

#### Thesis Submissions Table
```sql
thesis_submissions
├── id (Primary Key)
├── scholar_id (Foreign Key)
├── supervisor_id (Foreign Key)
├── title, abstract
├── file_path
├── status (pending_supervisor_approval, supervisor_approved, etc.)
├── submission_date
├── [Approval Workflow Fields]
└── timestamps
```

#### Thesis Submission Certificates Table
```sql
thesis_submission_certificates
├── id (Primary Key)
├── scholar_id (Foreign Key)
├── thesis_submission_id (Foreign Key)
├── certificate_type (pre_phd_presentation, research_papers, peer_reviewed_journal)
├── certificate_data (JSON)
├── status (pending, approved, generated)
├── generated_file_path
├── generated_by (Foreign Key)
└── timestamps
```

### Supporting Tables
- **roles** - User role definitions
- **departments** - Academic departments
- **supervisors** - Supervisor information
- **supervisor_assignments** - Scholar-supervisor relationships
- **synopses** - Synopsis submissions
- **progress_reports** - Progress tracking
- **office_notes** - Official registration notes
- **admissions** - Admission records

---

## User Roles & Workflows

### 1. Scholar
**Responsibilities:**
- Complete Ph.D. registration form
- Submit thesis
- Generate certificates after approval
- Track progress

**Key Workflows:**
```
Registration → Profile Setup → Thesis Submission → Certificate Generation
```

### 2. Supervisor
**Responsibilities:**
- Review scholar forms
- Approve thesis submissions
- Provide guidance

**Key Workflows:**
```
Review Forms → Approve/Reject → Provide Feedback
```

### 3. HOD (Head of Department)
**Responsibilities:**
- Department-level approvals
- Review supervisor recommendations

### 4. DA (Dean of Academic Affairs)
**Responsibilities:**
- Generate office notes
- Final academic approvals
- Certificate generation oversight

### 5. SO, AR, DR, HVC
**Responsibilities:**
- Multi-level approval process
- Quality assurance
- Final approvals

---

## Features & Modules

### 1. Scholar Registration System

#### Ph.D. Registration Form
**Location:** `/scholar/profile`
**Features:**
- 40+ registration fields
- Document upload capability
- Progress tracking
- Form validation

**Key Fields:**
- Personal Information
- Academic Background
- Research Details
- Supervisor Information
- Document Uploads

#### Form Status Workflow
```
not_started → in_progress → submitted → under_review → approved
```

### 2. Thesis Submission System

#### Thesis Submission Form
**Location:** `/scholar/thesis/submission-form`
**Features:**
- File upload (PDF, 50MB max)
- Supporting documents
- Abstract submission
- Validation

#### Thesis Status Tracking
**Location:** `/scholar/thesis/submissions/status`
**Features:**
- Real-time status updates
- Certificate generation
- Download capabilities

### 3. Certificate Generation System

#### Certificate Types
1. **Pre-Ph.D. Presentation Certificate**
   - Presentation date
   - Venue information
   - Supervisor signatures

2. **Research Papers Presentation Certificate**
   - Conference/seminar details
   - Presentation date
   - Venue information

3. **Peer Reviewed Journal Certificate**
   - Journal name
   - Publication date
   - Volume/issue details

#### Certificate Features
- Professional formatting
- Digital signatures
- PDF generation
- Print-ready output

### 4. Office Note System

#### DA Office Note Generation
**Location:** `/da/office-notes`
**Features:**
- Auto-populate scholar data
- Official document generation
- PDF download
- Status tracking

### 5. Approval Workflow System

#### Multi-level Approval Process
```
Scholar → Supervisor → HOD → DA → SO → AR → DR → HVC
```

#### Status Tracking
- Real-time updates
- Email notifications
- Progress indicators
- Audit trail

---

## API Endpoints

### Scholar Routes
```php
// Profile Management
GET  /scholar/profile                    # Edit Profile
PATCH /scholar/profile                   # Update Profile

// Registration Form
GET  /scholar/registration/phd-form      # Ph.D. Registration Form
POST /scholar/registration/phd-form      # Submit Registration

// Thesis Submission
GET  /scholar/thesis/submission-form     # Thesis Submission Form
POST /scholar/thesis/submit-new          # Submit Thesis
GET  /scholar/thesis/submissions/status  # Thesis Status

// Certificate Generation
POST /scholar/thesis/{thesis}/generate-certificate  # Generate Certificate
GET  /scholar/thesis/certificate/{certificate}      # View Certificate
GET  /scholar/thesis/certificate/{certificate}/download  # Download Certificate
```

### DA Routes
```php
// Office Notes
GET  /da/office-notes/eligible-scholars  # List Eligible Scholars
GET  /da/office-notes/generate/{scholar} # Generate Office Note
POST /da/office-notes/generate/{scholar} # Store Office Note
GET  /da/office-notes/{officeNote}       # View Office Note
GET  /da/office-notes/{officeNote}/download  # Download Office Note
```

### Supervisor Routes
```php
// Scholar Management
GET  /supervisor/scholars                # List Assigned Scholars
GET  /supervisor/scholars/{scholar}/form # Edit Scholar Form
PATCH /supervisor/scholars/{scholar}/form # Update Scholar Form
```

---

## Installation & Setup

### Prerequisites
- PHP 8.2 or higher
- Composer
- MySQL 8.0 or higher
- Node.js & NPM
- Git

### Installation Steps

1. **Clone Repository**
```bash
git clone <repository-url>
cd research-portal
```

2. **Install Dependencies**
```bash
composer install
npm install
```

3. **Environment Setup**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Database Configuration**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=uniraj_res
DB_USERNAME=root
DB_PASSWORD=your_password
```

5. **Run Migrations**
```bash
php artisan migrate:fresh --seed
```

6. **Build Assets**
```bash
npm run build
```

7. **Start Server**
```bash
php artisan serve
```

### Default Login Credentials

#### Scholar Account
- **Email:** scholar@example.com
- **Password:** password

#### Supervisor Account
- **Email:** supervisor@example.com
- **Password:** password

#### DA Account
- **Email:** da@example.com
- **Password:** password

---

## Configuration

### File Storage
```php
// config/filesystems.php
'public' => [
    'driver' => 'local',
    'root' => storage_path('app/public'),
    'url' => env('APP_URL').'/storage',
    'visibility' => 'public',
],
```

### Email Configuration
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
```

### Database Optimization
```php
// config/database.php
'mysql' => [
    'driver' => 'mysql',
    'url' => env('DATABASE_URL'),
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_DATABASE', 'forge'),
    'username' => env('DB_USERNAME', 'forge'),
    'password' => env('DB_PASSWORD', ''),
    'unix_socket' => env('DB_SOCKET', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'prefix_indexes' => true,
    'strict' => true,
    'engine' => null,
    'options' => extension_loaded('pdo_mysql') ? array_filter([
        PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
    ]) : [],
],
```

---

## Deployment

### Production Server Setup

1. **Server Requirements**
   - Ubuntu 20.04+ / CentOS 8+
   - PHP 8.2+ with extensions
   - MySQL 8.0+
   - Nginx/Apache
   - SSL Certificate

2. **Laravel Deployment**
```bash
# Install dependencies
composer install --optimize-autoloader --no-dev

# Generate optimized files
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

3. **Nginx Configuration**
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/research-portal/public;
    
    index index.php;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

4. **Database Migration**
```bash
php artisan migrate --force
php artisan db:seed --force
```

---

## Troubleshooting

### Common Issues

#### 1. Foreign Key Constraint Errors
**Error:** `SQLSTATE[HY000]: General error: 1005 Can't create table`
**Solution:**
```bash
# Disable foreign key checks temporarily
mysql -u root -p -e "SET FOREIGN_KEY_CHECKS=0;"
php artisan migrate:fresh --seed
mysql -u root -p -e "SET FOREIGN_KEY_CHECKS=1;"
```

#### 2. Permission Errors
**Error:** `Permission denied for storage/`
**Solution:**
```bash
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache
```

#### 3. Route Not Found
**Error:** `Route [route.name] not defined`
**Solution:**
```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

#### 4. File Upload Issues
**Error:** `File too large`
**Solution:**
```php
// config/filesystems.php - Increase upload limits
'upload_max_filesize' => '50M',
'post_max_size' => '50M',
```

### Debug Mode
```env
APP_DEBUG=true
LOG_LEVEL=debug
```

### Log Files
- **Laravel Logs:** `storage/logs/laravel.log`
- **Web Server Logs:** `/var/log/nginx/error.log`
- **PHP Logs:** `/var/log/php8.2-fpm.log`

---

## Security Considerations

### Authentication
- Laravel Breeze implementation
- CSRF protection on all forms
- Password hashing with bcrypt
- Session management

### File Upload Security
- File type validation
- File size limits
- Virus scanning (recommended)
- Secure file storage

### Database Security
- Prepared statements
- Input validation
- SQL injection prevention
- Regular backups

---

## Performance Optimization

### Database Optimization
- Proper indexing
- Query optimization
- Connection pooling
- Regular maintenance

### Caching Strategy
- Route caching
- Config caching
- View caching
- Database query caching

### File Storage
- CDN integration (optional)
- Image optimization
- File compression
- Cleanup old files

---

## Backup & Recovery

### Database Backup
```bash
# Daily backup script
mysqldump -u root -p uniraj_res > backup_$(date +%Y%m%d).sql
```

### File Backup
```bash
# Backup storage directory
tar -czf storage_backup_$(date +%Y%m%d).tar.gz storage/
```

### Recovery Process
```bash
# Restore database
mysql -u root -p uniraj_res < backup_20240101.sql

# Restore files
tar -xzf storage_backup_20240101.tar.gz
```

---

## Maintenance

### Regular Tasks
- **Daily:** Check logs, monitor disk space
- **Weekly:** Database optimization, backup verification
- **Monthly:** Security updates, performance review
- **Quarterly:** Full system backup, disaster recovery test

### Monitoring
- Server resource usage
- Database performance
- Application errors
- User activity logs

---

## Support & Contact

### Technical Support
- **Documentation:** This file
- **Issues:** GitHub Issues
- **Email:** support@uniraj.edu

### Development Team
- **Lead Developer:** [Name]
- **Database Admin:** [Name]
- **System Admin:** [Name]

---

## Version History

### v1.0.0 (Current)
- Initial release
- Scholar registration system
- Thesis submission workflow
- Certificate generation
- Multi-level approval process

### Future Releases
- v1.1.0: Mobile app integration
- v1.2.0: Advanced reporting
- v2.0.0: Multi-university support

---

## License

This project is proprietary software developed for the University of Rajasthan. All rights reserved.

---

*Last Updated: January 2025*
*Documentation Version: 1.0.0*
