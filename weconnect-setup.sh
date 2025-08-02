#!/bin/bash

# Update packages
sudo apt update && sudo apt upgrade -y

# Install Apache, PHP, MariaDB
sudo apt install apache2 php libapache2-mod-php mariadb-server php-mysql -y

# Enable services
sudo systemctl enable apache2
sudo systemctl enable mariadb

# Start services
sudo systemctl start apache2
sudo systemctl start mariadb

# Secure MariaDB (non-interactive)
sudo mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED BY '989878'; FLUSH PRIVILEGES;"

# Create DB and import data
mysql -u root -p'989878' -e "CREATE DATABASE WECONDB;"
mysql -u root -p'989878' WECONDB < wecondb_bak.sql

# Clone project from GitHub
cd /var/www/html
sudo git clone https://github.com/tejas-kapade/WECONNECT .
sudo chown -R www-data:www-data /var/www/html
