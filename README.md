# Hostel Management System

A comprehensive hostel management system built with Laravel and Filament for managing student accommodation, room allocations, and hostel operations.

## Features

### Admin Panel
- **Dashboard Analytics**: Real-time statistics on occupancy rates, gender distribution, and allocation status
- **Hostel Management**: Create and manage multiple hostels with configurable capacities
- **Room Management**: Full CRUD operations for rooms with gender-specific allocation
- **Student Management**: Comprehensive student database with profile management
- **Smart Auto-Allocation**: Intelligent algorithm that allocates rooms based on:
  - Gender compatibility
  - Study level preferences
  - Room availability
  - Hostel capacity
- **Manual Allocation**: Override automatic allocation for specific cases
- **Room Change Requests**: Handle student requests for room changes
- **User & Permissions**: Role-based access control for administrators
- **PDF Receipt Generation**: Automated receipt generation for allocations
- **Email Notifications**: Automated notifications for allocations and receipts

### Student Portal
- View room assignments
- Download allocation receipts
- Submit room change requests
- View hostel information

## Tech Stack

- **Backend**: Laravel 12.x
- **Admin Panel**: Filament 3.x
- **Database**: SQLite (default) / MySQL / PostgreSQL
- **PDF Generation**: DomPDF
- **Permissions**: Spatie Laravel Permission
- **Frontend**: Tailwind CSS 4.0
- **Build Tool**: Vite

## Requirements

- PHP 8.2 or higher
- Composer
- Node.js & NPM
- SQLite (or MySQL/PostgreSQL)

## Installation

### 1. Clone the Repository

```bash
git clone <repository-url>
cd hostel
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node Dependencies

```bash
npm install
```

### 4. Environment Setup

Copy the example environment file:

```bash
cp .env.example .env
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Configure Environment

Edit the `.env` file and configure your settings:

**Database Configuration** (SQLite is default):
```env
DB_CONNECTION=sqlite
```

For MySQL/PostgreSQL, update:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hostel_db
DB_USERNAME=root
DB_PASSWORD=your_password
```

**Application Settings**:
```env
APP_NAME="Hostel Management System"
APP_URL=http://localhost:8000
```

**Mail Configuration** (for notifications):
```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email@example.com
MAIL_PASSWORD=your-password
MAIL_FROM_ADDRESS=noreply@unilak.ac.rw
MAIL_FROM_NAME="${APP_NAME}"
```

### 7. Create Database

For SQLite (default):
```bash
touch database/database.sqlite
```

For MySQL/PostgreSQL:
```bash
# Create database using your database client
# mysql -u root -p
# CREATE DATABASE hostel_db;
```

### 8. Run Migrations

```bash
php artisan migrate
```

### 9. Seed the Database

This is the **most important step** to get started. The seeder will create:
- Default admin user
- Sample hostels (Boys and Girls hostels)
- Sample rooms with different capacities
- Sample students
- Roles and permissions

```bash
php artisan db:seed
```

**Default Admin Credentials**:
- **Email**: `admin@unilak.ac.rw`
- **Password**: `password`

> ⚠️ **Important**: Change the default password after first login!

### 10. Build Frontend Assets

```bash
npm run build
```

## Running the Application

### Development Mode

You can run all services concurrently (recommended):

```bash
composer dev
```

This will start:
- Laravel development server (http://localhost:8000)
- Queue worker
- Log viewer
- Vite development server

### Manual Start (Alternative)

If you prefer to run services separately:

**Terminal 1 - Laravel Server**:
```bash
php artisan serve
```

**Terminal 2 - Queue Worker** (for email notifications):
```bash
php artisan queue:work
```

**Terminal 3 - Frontend Dev Server**:
```bash
npm run dev
```

### Accessing the Application

- **Admin Panel**: http://localhost:8000/admin
- **Student Portal**: http://localhost:8000
- **Login**: Use the default admin credentials above

## Key Artisan Commands

### Database Operations

```bash
# Fresh migration (warning: drops all tables)
php artisan migrate:fresh

# Fresh migration with seeding
php artisan migrate:fresh --seed

# Run seeders only
php artisan db:seed

# Run specific seeder
php artisan db:seed --class=HostelSeeder
```

### Queue Management

```bash
# Process queue jobs
php artisan queue:work

# Listen for new jobs (auto-reload)
php artisan queue:listen
```

### Cache Management

```bash
# Clear all caches
php artisan optimize:clear

# Clear specific caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Filament Commands

```bash
# Create Filament admin user
php artisan make:filament-user

# Upgrade Filament
php artisan filament:upgrade
```

## Database Structure

### Main Tables

- **users**: Admin users with role-based permissions
- **students**: Student records (name, reg number, gender, level, etc.)
- **hostels**: Hostel definitions (name, gender, capacity)
- **rooms**: Room records (number, capacity, hostel, gender, occupied count)
- **allocations**: Student room allocations
- **room_change_requests**: Student requests for room changes
- **receipts**: PDF receipts for allocations

## Available Seeders

1. **DatabaseSeeder**: Main seeder (runs all others)
2. **HostelSeeder**: Creates sample hostels
3. **RoomSeeder**: Creates sample rooms
4. **StudentSeeder**: Creates sample students
5. **RolePermissionSeeder**: Sets up roles and permissions

## Production Deployment

### 1. Set Environment to Production

```env
APP_ENV=production
APP_DEBUG=false
```

### 2. Optimize Application

```bash
# Install dependencies (no dev packages)
composer install --optimize-autoloader --no-dev

# Build production assets
npm run build

# Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 3. Set Proper Permissions

```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 4. Configure Web Server

**Nginx Example**:
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/hostel/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### 5. Setup Queue Worker (Supervisor)

```ini
[program:hostel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/hostel/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/path/to/hostel/storage/logs/worker.log
```

## Troubleshooting

### Common Issues

**Issue**: `Class "App\Models\..." not found`
```bash
composer dump-autoload
```

**Issue**: Permission errors on storage/cache
```bash
chmod -R 775 storage bootstrap/cache
```

**Issue**: Vite manifest not found
```bash
npm run build
```

**Issue**: Database not found
```bash
# For SQLite
touch database/database.sqlite
php artisan migrate
```

**Issue**: Queue jobs not processing
```bash
# Make sure queue worker is running
php artisan queue:work
```

## Testing

```bash
# Run all tests
composer test

# Or manually
php artisan test
```

## Support

For issues and questions, please open an issue in the repository or contact the development team.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Credits

Built with:
- [Laravel](https://laravel.com)
- [Filament](https://filamentphp.com)
- [Tailwind CSS](https://tailwindcss.com)
