#!/bin/bash

# Color variables
GREEN="\e[32m"
RED="\e[31m"
YELLOW="\e[33m"
BLUE="\e[34m"
RESET="\e[0m"

# Function to display status messages
function info() {
    echo -e "${BLUE}[INFO]${RESET} $1"
}

function success() {
    echo -e "${GREEN}[SUCCESS]${RESET} $1"
}

function error() {
    echo -e "${RED}[ERROR]${RESET} $1"
}

function warning() {
    echo -e "${YELLOW}[WARNING]${RESET} $1"
}

# 1. Update and upgrade packages
info "Updating and upgrading system packages..."
sudo apt update && sudo apt upgrade -y && success "System updated successfully." || error "System update failed."

# 2. Install Apache, PHP, MariaDB
info "Installing Apache, PHP, and MariaDB..."
sudo apt install apache2 php libapache2-mod-php mariadb-server php-mysql -y && success "LAMP stack installed successfully." || error "LAMP stack installation failed."

# 3. Enable services
info "Enabling Apache and MariaDB services..."
sudo systemctl enable apache2
sudo systemctl enable mariadb
success "Services enabled to start on boot."

# 4. Start services
info "Starting Apache and MariaDB services..."
sudo systemctl start apache2
sudo systemctl start mariadb
success "Services started successfully."

# 5. Secure MariaDB (non-interactive)
info "Securing MariaDB root user..."
sudo mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED BY '989878'; FLUSH PRIVILEGES;" && success "MariaDB root password updated." || error "Failed to secure MariaDB."

# 6. Clone project from GitHub
info "Setting up WeConnect project..."
cd /var/www/html
sudo rm -rf /var/www/html/*
sudo git clone https://github.com/tejas-kapade/WECONNECT . && success "Repository cloned successfully." || error "Failed to clone repository."
sudo chown -R www-data:www-data /var/www/html && success "Permissions set for Apache."

# 7. Create Database and Import Data
info "Creating database and importing data..."
mysql -u root -p'989878' -e "CREATE DATABASE WECONDB;" && success "Database WECONDB created." || warning "Database might already exist."
mysql -u root -p'989878' WECONDB < wecondb_bak.sql && success "Database imported successfully." || error "Failed to import database."

# 8. Check running services with Nmap
info "Installing Nmap and scanning localhost ports..."
sudo apt install nmap -y
nmap localhost

# 9. Final message
echo -e "${GREEN}✔✔✔ WeConnect setup has been completed successfully! ✔✔✔${RESET}"
