# phpBlog Installation Guide

This guide will walk you through the installation process of phpBlog 2.4.

## Prerequisites

Before installing phpBlog, ensure your server meets the following requirements:

- **PHP**: Version 7.0 or higher
- **MySQL**: Version 5.6 or higher
- **Web Server**: Apache or Nginx
- **PHP Extensions**: 
  - PDO
  - PDO_MySQL
  - GD (for image processing)
  - mbstring

## Step-by-Step Installation

### Step 1: Download phpBlog

Download the latest version from GitHub:

```bash
git clone https://github.com/ZelTroN-2k3/phpBlog_2.4_fixed.git
cd phpBlog_2.4_fixed
```

Or download the ZIP file and extract it to your web server directory.

### Step 2: Create Database

1. Access your MySQL server using phpMyAdmin, MySQL Workbench, or command line:

```bash
mysql -u root -p
```

2. Create a new database:

```sql
CREATE DATABASE phpblog CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

3. Exit MySQL:

```sql
exit;
```

### Step 3: Import Database Schema

Import the provided SQL file:

```bash
mysql -u root -p phpblog < database.sql
```

This will create all necessary tables and insert sample data including:
- Default admin user
- Sample categories
- A welcome post

### Step 4: Configure phpBlog

1. Open `config.php` in a text editor

2. Update the database connection settings:

```php
// Database Configuration
define('DB_HOST', 'localhost');        // Your database host
define('DB_USER', 'your_username');    // Your database username
define('DB_PASS', 'your_password');    // Your database password
define('DB_NAME', 'phpblog');          // Your database name
```

3. Update site settings (optional):

```php
// Site Configuration
define('SITE_NAME', 'Your Blog Name');
define('SITE_URL', 'http://yourdomain.com');
define('SITE_DESCRIPTION', 'Your Blog Description');
```

4. Set a secure key for sessions:

```php
// Security
define('SECURE_KEY', 'your_unique_secure_key_here');
```

### Step 5: Set File Permissions

Ensure the uploads directory is writable by the web server:

**Linux/Unix:**
```bash
chmod 755 uploads/
chown -R www-data:www-data uploads/
```

**Windows:**
Right-click on the `uploads` folder → Properties → Security → Edit → Add write permissions for the web server user (usually IUSR or IIS_IUSRS).

### Step 6: Configure Web Server

#### Apache

If using Apache, the default `.htaccess` is already configured. Ensure `mod_rewrite` is enabled:

```bash
sudo a2enmod rewrite
sudo service apache2 restart
```

#### Nginx

Add this to your Nginx server block:

```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}

location ~ \.php$ {
    fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
    fastcgi_index index.php;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    include fastcgi_params;
}
```

### Step 7: Access Your Site

1. Open your browser and navigate to:
   - Frontend: `http://localhost/phpblog/` or `http://yourdomain.com/`
   - Admin: `http://localhost/phpblog/admin/` or `http://yourdomain.com/admin/`

2. Login with default credentials:
   - Username: `admin`
   - Password: `admin123`

### Step 8: Post-Installation Security

**IMPORTANT**: Complete these security steps immediately:

1. **Change Admin Password**:
   - Login to admin panel
   - Go to your profile settings
   - Change the default password

2. **Update Database Credentials**:
   - Use a strong database password
   - Never use 'root' for production

3. **Secure Configuration File**:
   ```bash
   chmod 644 config.php
   ```

4. **Change Security Key**:
   - Generate a random secure key in `config.php`

5. **Disable Directory Listing**:
   Add to `.htaccess`:
   ```apache
   Options -Indexes
   ```

## Troubleshooting

### Common Issues

#### 1. Database Connection Error

**Problem**: "Connection Error: ..." message appears

**Solution**:
- Verify database credentials in `config.php`
- Ensure MySQL service is running
- Check if database exists
- Verify user has proper permissions

#### 2. Cannot Upload Images

**Problem**: Image upload fails

**Solution**:
- Check `uploads/` directory permissions (should be 755 or 777)
- Verify PHP `upload_max_filesize` and `post_max_size` in php.ini
- Ensure GD extension is installed

#### 3. Blank Admin Page

**Problem**: Admin pages show blank

**Solution**:
- Check PHP error logs
- Verify all files uploaded correctly
- Ensure PHP version meets requirements
- Check file permissions

#### 4. CSS Not Loading

**Problem**: Pages load without styling

**Solution**:
- Check if `assets/css/style.css` exists
- Verify web server can access static files
- Check browser console for 404 errors
- Clear browser cache

## Upgrading

To upgrade from a previous version:

1. Backup your database and files
2. Replace old files with new ones (keep config.php)
3. Run any database migration scripts
4. Clear cache and test functionality

## Getting Help

If you encounter issues:

1. Check the documentation
2. Search existing GitHub issues
3. Open a new issue with:
   - PHP version
   - MySQL version
   - Error messages
   - Steps to reproduce

## Next Steps

After installation:

1. Create new categories
2. Write your first post
3. Customize the design
4. Configure site settings
5. Set up regular backups

Enjoy using phpBlog!
