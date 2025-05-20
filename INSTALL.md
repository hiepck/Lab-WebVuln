# Installation Guide

## Server Requirements

- Ubuntu Server (tested on 20.04 LTS)
- Apache2
- PHP 7.4 or higher
- MySQL/MariaDB
- phpmyadmin (optional, for easier database management)

## Installation Steps

### 1. Update Ubuntu System

```bash
sudo apt update
sudo apt upgrade -y
```

### 2. Install required packages

```bash
sudo apt install apache2 mysql-server php libapache2-mod-php php-mysql php-curl php-gd php-mbstring php-xml php-zip unzip -y
```

### 3. Start and enable services

```bash
sudo systemctl start apache2
sudo systemctl enable apache2
sudo systemctl start mysql
sudo systemctl enable mysql
```

### 4. Create MySQL database and user

```bash
sudo mysql -e "CREATE DATABASE vbank;"
sudo mysql -e "CREATE USER 'vbank_user'@'localhost' IDENTIFIED BY 'vbank_password';"
sudo mysql -e "GRANT ALL PRIVILEGES ON vbank.* TO 'vbank_user'@'localhost';"
sudo mysql -e "FLUSH PRIVILEGES;"
```

### 5. Download and install the application

```bash
cd /var/www/html
sudo rm -rf index.html
sudo git clone https://github.com/yourusername/vulnerable-banking-system.git .
# OR copy files manually if not using git
```

### 6. Import database schema

```bash
sudo mysql vbank < database/schema.sql
```

### 7. Configure the application

```bash
sudo cp config/config.example.php config/config.php
sudo nano config/config.php
# Edit database connection details and save
```

### 8. Set permissions

```bash
sudo chown -R www-data:www-data /var/www/html
sudo chmod -R 755 /var/www/html
sudo chmod -R 777 /var/www/html/uploads  # Intentionally insecure for file upload vulnerability
```

### 9. Access the application

Open a browser and navigate to http://your_server_ip/

### Default credentials

- Admin: admin@vbank.local / admin123
- User: user@vbank.local / user123

## Security Warning

This is a deliberately vulnerable application. It should ONLY be deployed in:

- Isolated lab environments
- Private networks for training
- VMs with no internet access

Consider using a firewall to restrict access:

```bash
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw enable
```
