# Deployment Guide - Research Portal

## Table of Contents
1. [Server Requirements](#server-requirements)
2. [Pre-deployment Setup](#pre-deployment-setup)
3. [Application Deployment](#application-deployment)
4. [Database Setup](#database-setup)
5. [Web Server Configuration](#web-server-configuration)
6. [SSL Configuration](#ssl-configuration)
7. [Post-deployment Tasks](#post-deployment-tasks)
8. [Monitoring & Maintenance](#monitoring--maintenance)
9. [Troubleshooting](#troubleshooting)

---

## Server Requirements

### Minimum Requirements
- **OS:** Ubuntu 20.04 LTS or CentOS 8+
- **RAM:** 4GB (8GB recommended)
- **Storage:** 50GB SSD (100GB recommended)
- **CPU:** 2 cores (4 cores recommended)
- **Network:** 1Gbps connection

### Software Requirements
- **PHP:** 8.2 or higher
- **MySQL:** 8.0 or higher
- **Nginx:** 1.18+ or Apache 2.4+
- **Composer:** Latest version
- **Node.js:** 16+ and NPM
- **Git:** Latest version

### PHP Extensions Required
```bash
php8.2-cli
php8.2-fpm
php8.2-mysql
php8.2-xml
php8.2-mbstring
php8.2-curl
php8.2-zip
php8.2-gd
php8.2-intl
php8.2-bcmath
php8.2-fileinfo
php8.2-tokenizer
```

---

## Pre-deployment Setup

### 1. Update System
```bash
# Ubuntu/Debian
sudo apt update && sudo apt upgrade -y

# CentOS/RHEL
sudo yum update -y
```

### 2. Install Required Software

#### Ubuntu/Debian
```bash
# Install PHP 8.2
sudo apt install software-properties-common -y
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install php8.2 php8.2-cli php8.2-fpm php8.2-mysql php8.2-xml php8.2-mbstring php8.2-curl php8.2-zip php8.2-gd php8.2-intl php8.2-bcmath php8.2-fileinfo php8.2-tokenizer -y

# Install MySQL
sudo apt install mysql-server-8.0 -y

# Install Nginx
sudo apt install nginx -y

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install nodejs -y

# Install Git
sudo apt install git -y
```

#### CentOS/RHEL
```bash
# Install EPEL repository
sudo yum install epel-release -y

# Install Remi repository
sudo yum install https://rpms.remirepo.net/enterprise/remi-release-8.rpm -y

# Install PHP 8.2
sudo yum module enable php:remi-8.2 -y
sudo yum install php php-cli php-fpm php-mysql php-xml php-mbstring php-curl php-zip php-gd php-intl php-bcmath php-fileinfo php-tokenizer -y

# Install MySQL
sudo yum install mysql-server -y

# Install Nginx
sudo yum install nginx -y

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://rpm.nodesource.com/setup_18.x | sudo bash -
sudo yum install nodejs -y

# Install Git
sudo yum install git -y
```

### 3. Configure PHP
```bash
# Edit PHP configuration
sudo nano /etc/php/8.2/fpm/php.ini

# Update the following settings:
memory_limit = 256M
upload_max_filesize = 50M
post_max_size = 50M
max_execution_time = 300
max_input_vars = 3000
date.timezone = Asia/Kolkata
```

### 4. Configure PHP-FPM
```bash
# Edit PHP-FPM configuration
sudo nano /etc/php/8.2/fpm/pool.d/www.conf

# Update the following settings:
user = www-data
group = www-data
listen = /run/php/php8.2-fpm.sock
listen.owner = www-data
listen.group = www-data
listen.mode = 0660
```

---

## Application Deployment

### 1. Clone Repository
```bash
# Create application directory
sudo mkdir -p /var/www/research-portal
sudo chown -R www-data:www-data /var/www/research-portal

# Clone repository
cd /var/www
sudo -u www-data git clone <repository-url> research-portal
cd research-portal
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
sudo -u www-data composer install --optimize-autoloader --no-dev

# Install Node.js dependencies
sudo -u www-data npm install
sudo -u www-data npm run build
```

### 3. Environment Configuration
```bash
# Copy environment file
sudo -u www-data cp .env.example .env

# Generate application key
sudo -u www-data php artisan key:generate

# Edit environment file
sudo -u www-data nano .env
```

### 4. Environment Variables
```env
APP_NAME="Research Portal"
APP_ENV=production
APP_KEY=base64:your-generated-key
APP_DEBUG=false
APP_URL=https://your-domain.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=uniraj_res
DB_USERNAME=research_portal_user
DB_PASSWORD=your-secure-password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@your-domain.com
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

---

## Database Setup

### 1. Secure MySQL Installation
```bash
# Start MySQL service
sudo systemctl start mysql
sudo systemctl enable mysql

# Secure MySQL installation
sudo mysql_secure_installation
```

### 2. Create Database and User
```sql
-- Login to MySQL
sudo mysql -u root -p

-- Create database
CREATE DATABASE uniraj_res CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create user
CREATE USER 'research_portal_user'@'localhost' IDENTIFIED BY 'your-secure-password';

-- Grant privileges
GRANT ALL PRIVILEGES ON uniraj_res.* TO 'research_portal_user'@'localhost';
FLUSH PRIVILEGES;

-- Exit MySQL
EXIT;
```

### 3. Run Migrations
```bash
# Set proper permissions
sudo chown -R www-data:www-data /var/www/research-portal
sudo chmod -R 755 /var/www/research-portal
sudo chmod -R 775 /var/www/research-portal/storage
sudo chmod -R 775 /var/www/research-portal/bootstrap/cache

# Run migrations
cd /var/www/research-portal
sudo -u www-data php artisan migrate --force

# Seed database
sudo -u www-data php artisan db:seed --force
```

---

## Web Server Configuration

### Nginx Configuration

#### 1. Create Nginx Configuration
```bash
sudo nano /etc/nginx/sites-available/research-portal
```

#### 2. Nginx Configuration Content
```nginx
server {
    listen 80;
    server_name your-domain.com www.your-domain.com;
    root /var/www/research-portal/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    # Handle Laravel routes
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # Handle PHP files
    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Deny access to hidden files
    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Security headers
    add_header X-XSS-Protection "1; mode=block";
    add_header X-Robots-Tag "noindex, nofollow";
    add_header Referrer-Policy "strict-origin-when-cross-origin";

    # File upload limits
    client_max_body_size 50M;
}

# Redirect HTTP to HTTPS (after SSL setup)
# server {
#     listen 80;
#     server_name your-domain.com www.your-domain.com;
#     return 301 https://$server_name$request_uri;
# }
```

#### 3. Enable Site
```bash
# Enable site
sudo ln -s /etc/nginx/sites-available/research-portal /etc/nginx/sites-enabled/

# Remove default site
sudo rm /etc/nginx/sites-enabled/default

# Test configuration
sudo nginx -t

# Restart Nginx
sudo systemctl restart nginx
```

### Apache Configuration (Alternative)

#### 1. Enable Required Modules
```bash
sudo a2enmod rewrite
sudo a2enmod ssl
sudo a2enmod headers
```

#### 2. Create Virtual Host
```bash
sudo nano /etc/apache2/sites-available/research-portal.conf
```

#### 3. Apache Configuration Content
```apache
<VirtualHost *:80>
    ServerName your-domain.com
    ServerAlias www.your-domain.com
    DocumentRoot /var/www/research-portal/public

    <Directory /var/www/research-portal/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/research-portal_error.log
    CustomLog ${APACHE_LOG_DIR}/research-portal_access.log combined
</VirtualHost>
```

#### 4. Enable Site
```bash
sudo a2ensite research-portal.conf
sudo systemctl restart apache2
```

---

## SSL Configuration

### 1. Install Certbot
```bash
# Ubuntu/Debian
sudo apt install certbot python3-certbot-nginx -y

# CentOS/RHEL
sudo yum install certbot python3-certbot-nginx -y
```

### 2. Obtain SSL Certificate
```bash
# Get SSL certificate
sudo certbot --nginx -d your-domain.com -d www.your-domain.com

# Test auto-renewal
sudo certbot renew --dry-run
```

### 3. Update Nginx Configuration for HTTPS
```nginx
server {
    listen 443 ssl http2;
    server_name your-domain.com www.your-domain.com;
    root /var/www/research-portal/public;

    # SSL Configuration
    ssl_certificate /etc/letsencrypt/live/your-domain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/your-domain.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512:ECDHE-RSA-AES256-GCM-SHA384:DHE-RSA-AES256-GCM-SHA384;
    ssl_prefer_server_ciphers off;
    ssl_session_cache shared:SSL:10m;
    ssl_session_timeout 10m;

    # Security headers
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;

    # Rest of the configuration remains the same
    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    client_max_body_size 50M;
}
```

---

## Post-deployment Tasks

### 1. Optimize Laravel
```bash
cd /var/www/research-portal

# Clear and cache configuration
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache

# Optimize autoloader
sudo -u www-data composer dump-autoload --optimize
```

### 2. Set Up Cron Jobs
```bash
# Edit crontab
sudo crontab -e

# Add Laravel scheduler
* * * * * cd /var/www/research-portal && php artisan schedule:run >> /dev/null 2>&1
```

### 3. Set Up Log Rotation
```bash
# Create logrotate configuration
sudo nano /etc/logrotate.d/research-portal

# Add content
/var/www/research-portal/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    notifempty
    create 644 www-data www-data
}
```

### 4. Configure Firewall
```bash
# Ubuntu/Debian (UFW)
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable

# CentOS/RHEL (firewalld)
sudo firewall-cmd --permanent --add-service=ssh
sudo firewall-cmd --permanent --add-service=http
sudo firewall-cmd --permanent --add-service=https
sudo firewall-cmd --reload
```

### 5. Set Up Monitoring
```bash
# Install monitoring tools
sudo apt install htop iotop nethogs -y

# Create monitoring script
sudo nano /usr/local/bin/monitor-research-portal.sh
```

#### Monitoring Script Content
```bash
#!/bin/bash

# Check if services are running
if ! systemctl is-active --quiet nginx; then
    echo "Nginx is not running!" | mail -s "Research Portal Alert" admin@your-domain.com
fi

if ! systemctl is-active --quiet mysql; then
    echo "MySQL is not running!" | mail -s "Research Portal Alert" admin@your-domain.com
fi

if ! systemctl is-active --quiet php8.2-fpm; then
    echo "PHP-FPM is not running!" | mail -s "Research Portal Alert" admin@your-domain.com
fi

# Check disk space
DISK_USAGE=$(df / | awk 'NR==2 {print $5}' | sed 's/%//')
if [ $DISK_USAGE -gt 80 ]; then
    echo "Disk usage is at ${DISK_USAGE}%" | mail -s "Research Portal Disk Alert" admin@your-domain.com
fi

# Check memory usage
MEMORY_USAGE=$(free | awk 'NR==2{printf "%.2f", $3*100/$2}')
if (( $(echo "$MEMORY_USAGE > 90" | bc -l) )); then
    echo "Memory usage is at ${MEMORY_USAGE}%" | mail -s "Research Portal Memory Alert" admin@your-domain.com
fi
```

```bash
# Make script executable
sudo chmod +x /usr/local/bin/monitor-research-portal.sh

# Add to crontab for hourly monitoring
sudo crontab -e
# Add: 0 * * * * /usr/local/bin/monitor-research-portal.sh
```

---

## Monitoring & Maintenance

### 1. Daily Tasks
```bash
# Check service status
sudo systemctl status nginx mysql php8.2-fpm

# Check disk space
df -h

# Check memory usage
free -h

# Check application logs
tail -f /var/www/research-portal/storage/logs/laravel.log
```

### 2. Weekly Tasks
```bash
# Update system packages
sudo apt update && sudo apt upgrade -y

# Check SSL certificate expiry
sudo certbot certificates

# Optimize database
sudo -u www-data php artisan optimize:clear
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache
```

### 3. Monthly Tasks
```bash
# Backup database
mysqldump -u research_portal_user -p uniraj_res > backup_$(date +%Y%m%d).sql

# Backup application files
tar -czf research-portal-backup-$(date +%Y%m%d).tar.gz /var/www/research-portal

# Review and clean old logs
sudo find /var/www/research-portal/storage/logs -name "*.log" -mtime +30 -delete
```

---

## Troubleshooting

### Common Issues

#### 1. 500 Internal Server Error
```bash
# Check Laravel logs
tail -f /var/www/research-portal/storage/logs/laravel.log

# Check Nginx error logs
tail -f /var/log/nginx/error.log

# Check PHP-FPM logs
tail -f /var/log/php8.2-fpm.log

# Common fixes
sudo chmod -R 775 /var/www/research-portal/storage
sudo chmod -R 775 /var/www/research-portal/bootstrap/cache
sudo -u www-data php artisan config:clear
```

#### 2. Database Connection Error
```bash
# Check MySQL status
sudo systemctl status mysql

# Test database connection
mysql -u research_portal_user -p -e "SELECT 1;"

# Check Laravel database configuration
sudo -u www-data php artisan tinker
# In tinker: DB::connection()->getPdo();
```

#### 3. File Permission Issues
```bash
# Fix ownership
sudo chown -R www-data:www-data /var/www/research-portal

# Fix permissions
sudo chmod -R 755 /var/www/research-portal
sudo chmod -R 775 /var/www/research-portal/storage
sudo chmod -R 775 /var/www/research-portal/bootstrap/cache
```

#### 4. SSL Certificate Issues
```bash
# Check certificate status
sudo certbot certificates

# Renew certificate
sudo certbot renew

# Test SSL configuration
openssl s_client -connect your-domain.com:443 -servername your-domain.com
```

### Performance Issues

#### 1. Slow Page Load
```bash
# Check PHP-FPM configuration
sudo nano /etc/php/8.2/fpm/pool.d/www.conf

# Increase process limits
pm.max_children = 20
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 10

# Restart PHP-FPM
sudo systemctl restart php8.2-fpm
```

#### 2. High Memory Usage
```bash
# Check memory usage
free -h
ps aux --sort=-%mem | head

# Optimize PHP memory settings
sudo nano /etc/php/8.2/fpm/php.ini
# memory_limit = 256M
```

### Security Issues

#### 1. Unauthorized Access
```bash
# Check access logs
sudo tail -f /var/log/nginx/access.log

# Block suspicious IPs
sudo iptables -A INPUT -s suspicious-ip -j DROP

# Update firewall rules
sudo ufw deny from suspicious-ip
```

#### 2. File Upload Issues
```bash
# Check file upload limits
php -i | grep upload_max_filesize
php -i | grep post_max_size

# Update limits in php.ini
sudo nano /etc/php/8.2/fpm/php.ini
# upload_max_filesize = 50M
# post_max_size = 50M
```

---

## Backup & Recovery

### Automated Backup Script
```bash
#!/bin/bash
# /usr/local/bin/backup-research-portal.sh

BACKUP_DIR="/var/backups/research-portal"
DATE=$(date +%Y%m%d_%H%M%S)
DB_NAME="uniraj_res"
DB_USER="research_portal_user"
DB_PASS="your-secure-password"
APP_DIR="/var/www/research-portal"

# Create backup directory
mkdir -p $BACKUP_DIR

# Database backup
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME > $BACKUP_DIR/database_$DATE.sql

# Application files backup
tar -czf $BACKUP_DIR/application_$DATE.tar.gz $APP_DIR

# Keep only last 7 days of backups
find $BACKUP_DIR -name "*.sql" -mtime +7 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +7 -delete

echo "Backup completed: $DATE"
```

### Recovery Procedure
```bash
# Stop services
sudo systemctl stop nginx php8.2-fpm

# Restore database
mysql -u research_portal_user -p uniraj_res < backup_20240101_120000.sql

# Restore application files
tar -xzf application_20240101_120000.tar.gz -C /

# Fix permissions
sudo chown -R www-data:www-data /var/www/research-portal
sudo chmod -R 755 /var/www/research-portal
sudo chmod -R 775 /var/www/research-portal/storage
sudo chmod -R 775 /var/www/research-portal/bootstrap/cache

# Start services
sudo systemctl start php8.2-fpm nginx

# Clear caches
cd /var/www/research-portal
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache
```

---

## Security Checklist

### Server Security
- [ ] Firewall configured and enabled
- [ ] SSH key authentication enabled
- [ ] Regular security updates applied
- [ ] Unnecessary services disabled
- [ ] Strong passwords used
- [ ] SSL certificate installed and valid

### Application Security
- [ ] Environment variables secured
- [ ] File permissions properly set
- [ ] Database credentials secured
- [ ] CSRF protection enabled
- [ ] Input validation implemented
- [ ] File upload restrictions in place

### Monitoring
- [ ] Log monitoring configured
- [ ] Backup system in place
- [ ] Performance monitoring active
- [ ] Security alerts configured
- [ ] Regular security audits scheduled

---

*Last Updated: January 2025*
*Deployment Guide Version: 1.0.0*
