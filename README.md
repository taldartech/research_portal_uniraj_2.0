# Research Portal - University of Rajasthan

A comprehensive web application for managing the entire Ph.D. research lifecycle, from scholar registration to thesis submission and certificate generation.

## 🚀 Quick Start

### Prerequisites
- PHP 8.2+
- MySQL 8.0+
- Composer
- Node.js & NPM

### Installation
```bash
# Clone the repository
git clone <repository-url>
cd research-portal

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Configure database in .env file
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=uniraj_res
DB_USERNAME=root
DB_PASSWORD=your_password

# Run migrations and seeders
php artisan migrate:fresh --seed

# Build assets
npm run build

# Start server
php artisan serve
```

### Default Login Credentials
- **Scholar:** scholar@example.com / password
- **Supervisor:** supervisor@example.com / password
- **DA:** da@example.com / password

## 📋 Features

### ✅ Scholar Management
- Complete Ph.D. registration form with 40+ fields
- Profile management and document upload
- Thesis submission with file validation
- Certificate generation after approval

### ✅ Approval Workflow
- Multi-level approval process: Scholar → Supervisor → HOD → DA → SO → AR → DR → HVC
- Real-time status tracking
- Email notifications
- Progress indicators

### ✅ Certificate Generation
- **Pre-Ph.D. Presentation Certificate**
- **Research Papers Presentation Certificate**
- **Peer Reviewed Journal Certificate**
- Professional PDF formatting with digital signatures

### ✅ Office Note System
- DA-generated official registration notes
- Auto-populated scholar data
- PDF download capability

### ✅ Document Management
- Secure file upload and storage
- Multiple file format support
- File validation and size limits

## 🏗️ System Architecture

### User Roles
```
Scholar → Supervisor → HOD → DA → SO → AR → DR → HVC
```

### Key Modules
- **Scholar Registration System**
- **Thesis Submission & Approval**
- **Certificate Generation**
- **Office Note Management**
- **Progress Tracking**
- **Document Management**

## 📊 Database Schema

### Core Tables
- `users` - User authentication and roles
- `scholars` - Scholar profiles and registration data
- `thesis_submissions` - Thesis submission records
- `thesis_submission_certificates` - Generated certificates
- `office_notes` - Official registration notes
- `supervisor_assignments` - Scholar-supervisor relationships

## 🔧 Configuration

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

### Email Settings
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
```

## 🚀 Deployment

### Production Setup
1. **Server Requirements**
   - Ubuntu 20.04+ / CentOS 8+
   - PHP 8.2+ with required extensions
   - MySQL 8.0+
   - Nginx/Apache with SSL

2. **Deployment Commands**
```bash
# Install production dependencies
composer install --optimize-autoloader --no-dev

# Generate optimized files
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## 🛠️ Development

### Code Structure
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

### Key Controllers
- `ScholarController` - Scholar management and thesis submission
- `DAController` - Office note generation and management
- `SupervisorController` - Supervisor workflows
- `HODController` - Department-level approvals

### Key Models
- `Scholar` - Scholar profiles and registration
- `ThesisSubmission` - Thesis submission records
- `ThesisSubmissionCertificate` - Certificate management
- `OfficeNote` - Official registration notes

## 🔍 API Endpoints

### Scholar Routes
```php
GET  /scholar/profile                    # Edit Profile
GET  /scholar/registration/phd-form      # Ph.D. Registration Form
POST /scholar/registration/phd-form      # Submit Registration
GET  /scholar/thesis/submission-form     # Thesis Submission Form
POST /scholar/thesis/submit-new          # Submit Thesis
GET  /scholar/thesis/submissions/status  # Thesis Status
```

### DA Routes
```php
GET  /da/office-notes/eligible-scholars  # List Eligible Scholars
GET  /da/office-notes/generate/{scholar} # Generate Office Note
POST /da/office-notes/generate/{scholar} # Store Office Note
```

## 🐛 Troubleshooting

### Common Issues

#### Foreign Key Constraint Errors
```bash
mysql -u root -p -e "SET FOREIGN_KEY_CHECKS=0;"
php artisan migrate:fresh --seed
mysql -u root -p -e "SET FOREIGN_KEY_CHECKS=1;"
```

#### Permission Errors
```bash
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache
```

#### Route Not Found
```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

## 📈 Performance

### Optimization
- Route caching
- Config caching
- View caching
- Database query optimization
- File storage optimization

### Monitoring
- Server resource usage
- Database performance
- Application errors
- User activity logs

## 🔒 Security

### Features
- Laravel Breeze authentication
- CSRF protection
- Password hashing
- File upload validation
- SQL injection prevention
- XSS protection

## 📚 Documentation

- **Complete Documentation:** [PROJECT_DOCUMENTATION.md](./PROJECT_DOCUMENTATION.md)
- **API Reference:** See API Endpoints section
- **Database Schema:** See Database Schema section

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## 📄 License

This project is proprietary software developed for the University of Rajasthan. All rights reserved.

## 📞 Support

- **Technical Issues:** Create an issue on GitHub
- **Documentation:** See PROJECT_DOCUMENTATION.md
- **Email:** support@uniraj.edu

---

**Version:** 1.0.0  
**Last Updated:** January 2025  
**Laravel Version:** 11.x  
**PHP Version:** 8.2+
