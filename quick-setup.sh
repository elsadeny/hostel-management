#!/bin/bash

#############################################################################
# Hostel Management System - Ultimate Setup Script for Ubuntu 24.04
#############################################################################
# This script handles installation, setup, and maintenance tasks.
#
# Usage: 
#   # Install/Setup
#   wget -O - https://raw.githubusercontent.com/elsadeny/hostel-management/main/quick-setup.sh | sudo bash
#
#   # Fix Permissions only
#   sudo bash quick-setup.sh --fix-permissions
#
#############################################################################

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

print_success() { echo -e "${GREEN}✓ $1${NC}"; }
print_info() { echo -e "${BLUE}ℹ $1${NC}"; }
print_warning() { echo -e "${YELLOW}⚠ $1${NC}"; }
print_error() { echo -e "${RED}✗ $1${NC}"; }
print_section() {
    echo ""
    echo -e "${BLUE}========================================${NC}"
    echo -e "${BLUE}$1${NC}"
    echo -e "${BLUE}========================================${NC}"
}

# Check root
if [ "$EUID" -ne 0 ]; then 
    print_error "Please run as root (use sudo)"
    exit 1
fi

ACTUAL_USER=${SUDO_USER:-$USER}
APP_DIR="/var/www/hostel"
GIT_REPO="https://github.com/elsadeny/hostel-management.git"

#############################################################################
# Function: Fix Permissions
#############################################################################
fix_permissions() {
    print_section "Fixing Permissions"
    
    if [ ! -d "$APP_DIR" ]; then
        print_error "Directory $APP_DIR does not exist"
        return
    fi

    print_info "Setting ownership to $ACTUAL_USER:www-data..."
    chown -R $ACTUAL_USER:www-data "$APP_DIR"
    
    print_info "Setting directory permissions..."
    find "$APP_DIR" -type d -exec chmod 755 {} \;
    find "$APP_DIR" -type f -exec chmod 644 {} \;
    
    print_info "Configuring storage permissions..."
    chown -R www-data:www-data "$APP_DIR/storage"
    chown -R www-data:www-data "$APP_DIR/bootstrap/cache"
    chown -R www-data:www-data "$APP_DIR/database"
    
    chmod -R 775 "$APP_DIR/storage"
    chmod -R 775 "$APP_DIR/bootstrap/cache"
    chmod -R 775 "$APP_DIR/database"
    
    # Fix SQLite database file if exists
    if [ -f "$APP_DIR/database/database.sqlite" ]; then
        print_info "Fixing SQLite database file..."
        chown www-data:www-data "$APP_DIR/database/database.sqlite"
        chmod 664 "$APP_DIR/database/database.sqlite"
    fi
    
    # Fix session cache if exists
    if [ -f "$APP_DIR/storage/framework/cache/data" ]; then
        chown -R www-data:www-data "$APP_DIR/storage/framework/cache/data"
        chmod -R 775 "$APP_DIR/storage/framework/cache/data"
    fi

    print_success "Permissions fixed successfully"
}

#############################################################################
# Main Installation Logic
#############################################################################
install_app() {
    print_section "Hostel Management System - Quick Setup"
    print_info "GitHub: $GIT_REPO"
    print_info "User: $ACTUAL_USER"

    # System Update
    print_section "Updating System"
    apt update && apt upgrade -y
    print_success "System updated"

    # Install Dependencies
    print_section "Installing Dependencies"
    apt install -y software-properties-common curl wget git unzip apt-transport-https ca-certificates gnupg lsb-release
    print_success "Basic dependencies installed"

    # Install PHP 8.2
    print_section "Installing PHP 8.2"
    add-apt-repository -y ppa:ondrej/php
    apt update
    apt install -y php8.2 php8.2-cli php8.2-common php8.2-fpm php8.2-mysql php8.2-pgsql \
        php8.2-sqlite3 php8.2-zip php8.2-gd php8.2-mbstring php8.2-curl php8.2-xml \
        php8.2-bcmath php8.2-intl php8.2-redis php8.2-opcache
    print_success "PHP 8.2 installed"

    # Install Composer
    print_section "Installing Composer"
    if ! command -v composer &> /dev/null; then
        EXPECTED_CHECKSUM="$(php -r 'copy("https://composer.github.io/installer.sig", "php://stdout");')"
        php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
        ACTUAL_CHECKSUM="$(php -r "echo hash_file('sha384', 'composer-setup.php');")"

        if [ "$EXPECTED_CHECKSUM" != "$ACTUAL_CHECKSUM" ]; then
            print_error "Composer installer corrupt"
            rm composer-setup.php
            exit 1
        fi

        php composer-setup.php --quiet
        rm composer-setup.php
        mv composer.phar /usr/local/bin/composer
        chmod +x /usr/local/bin/composer
    fi
    print_success "Composer installed"

    # Install Node.js
    print_section "Installing Node.js"
    if ! command -v node &> /dev/null; then
        curl -fsSL https://deb.nodesource.com/setup_22.x | bash -
        apt install -y nodejs
    fi
    print_success "Node.js installed"

    # Install SQLite
    print_section "Installing SQLite"
    apt install -y sqlite3
    print_success "SQLite installed"

    # Install Nginx
    print_section "Installing Nginx"
    apt install -y nginx
    systemctl start nginx
    systemctl enable nginx
    print_success "Nginx installed"

    # Clone Repository
    print_section "Cloning Application"
    if [ -d "$APP_DIR" ]; then
        print_warning "Directory $APP_DIR already exists"
        read -p "Remove and re-clone? (y/N): " -n 1 -r
        echo
        if [[ $REPLY =~ ^[Yy]$ ]]; then
            rm -rf "$APP_DIR"
            git clone "$GIT_REPO" "$APP_DIR"
        else
            print_info "Using existing directory"
        fi
    else
        git clone "$GIT_REPO" "$APP_DIR"
    fi
    
    cd "$APP_DIR"
    chown -R $ACTUAL_USER:$ACTUAL_USER "$APP_DIR"
    print_success "Repository ready"

    # Install App Dependencies
    print_section "Installing Application Dependencies"
    sudo -u $ACTUAL_USER composer install --optimize-autoloader --no-interaction
    sudo -u $ACTUAL_USER npm install
    print_success "Dependencies installed"

    # Environment Setup
    print_section "Configuring Environment"
    if [ ! -f .env ]; then
        sudo -u $ACTUAL_USER cp .env.example .env
        print_success ".env file created"
    fi

    sudo -u $ACTUAL_USER php artisan key:generate --force
    
    # Database Setup
    if [ ! -f database/database.sqlite ]; then
        sudo -u $ACTUAL_USER touch database/database.sqlite
        print_success "SQLite database created"
    fi

    # Run Migrations & Seeds
    print_section "Database Migration"
    sudo -u $ACTUAL_USER php artisan migrate --force
    sudo -u $ACTUAL_USER php artisan db:seed --force
    print_success "Database migrated and seeded"

    # Build Assets
    print_section "Building Assets"
    sudo -u $ACTUAL_USER npm run build
    print_success "Assets built"

    # Optimize
    print_section "Optimizing Application"
    sudo -u $ACTUAL_USER php artisan config:cache
    sudo -u $ACTUAL_USER php artisan route:cache
    sudo -u $ACTUAL_USER php artisan view:cache
    print_success "Application optimized"

    # Fix Permissions (Call function)
    fix_permissions

    # Configure Nginx
    print_section "Configuring Nginx"
    SERVER_IP=$(hostname -I | awk '{print $1}')
    read -p "Enter domain name (or press Enter to use IP: $SERVER_IP): " DOMAIN_NAME
    DOMAIN_NAME=${DOMAIN_NAME:-$SERVER_IP}

    NGINX_CONFIG="/etc/nginx/sites-available/hostel"
    cat > "$NGINX_CONFIG" << EOF
server {
    listen 80;
    listen [::]:80;
    server_name $DOMAIN_NAME;
    root $APP_DIR/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;
    charset utf-8;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF

    ln -sf "$NGINX_CONFIG" /etc/nginx/sites-enabled/hostel
    rm -f /etc/nginx/sites-enabled/default
    nginx -t && systemctl reload nginx
    print_success "Nginx configured"

    # Setup Supervisor
    print_section "Setting Up Queue Worker"
    apt install -y supervisor
    SUPERVISOR_CONFIG="/etc/supervisor/conf.d/hostel-worker.conf"
    cat > "$SUPERVISOR_CONFIG" << EOF
[program:hostel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php $APP_DIR/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=$ACTUAL_USER
numprocs=1
redirect_stderr=true
stdout_logfile=$APP_DIR/storage/logs/worker.log
stopwaitsecs=3600
EOF
    supervisorctl reread
    supervisorctl update
    supervisorctl start hostel-worker:*
    print_success "Supervisor configured"

    # Firewall
    print_section "Configuring Firewall"
    ufw --force enable
    ufw allow 22/tcp
    ufw allow 80/tcp
    ufw allow 443/tcp
    print_success "Firewall configured"

    # Final Summary
    print_section "🎉 Installation Complete!"
    echo ""
    echo "   🌐 Application URL:  http://$DOMAIN_NAME"
    echo "   🔐 Admin Panel:      http://$DOMAIN_NAME/admin"
    echo "   📧 Email:            admin@unilak.ac.rw"
    echo "   🔑 Password:         password"
    echo ""
    print_warning "⚠️  IMPORTANT: Change the password after first login!"
}

#############################################################################
# Main Entry Point
#############################################################################

# Handle arguments
if [ "$1" == "--fix-permissions" ]; then
    fix_permissions
    exit 0
fi

# Default to full installation
install_app
