# phpBlog 2.4 - Multi-Purpose CMS

phpBlog is a clean, simple, lightweight, responsive and user-friendly Content Management System. It can be used for blogs, portals, company and agency websites, magazines, newspapers and many other purposes.

## Features

- **Multi-Purpose CMS**: Suitable for blogs, portals, magazines, company websites, and more
- **Clean & Simple**: Easy to use interface and clean code structure
- **Lightweight**: Fast loading and minimal resource usage
- **Responsive Design**: Mobile-friendly and works on all devices
- **User-Friendly**: Intuitive admin panel for content management
- **Post Management**: Create, edit, and delete blog posts with ease
- **Category System**: Organize content with categories
- **Media Upload**: Upload and manage images for posts
- **User Authentication**: Secure login system for admin panel
- **Modern Design**: Beautiful gradient-based UI with smooth transitions

## Requirements

- PHP 7.0 or higher
- MySQL 5.6 or higher
- Apache/Nginx web server
- PDO PHP Extension

## Installation

1. **Clone or Download** the repository:
   ```bash
   git clone https://github.com/ZelTroN-2k3/phpBlog_2.4_fixed.git
   ```

2. **Import Database**:
   - Create a new MySQL database named `phpblog`
   - Import the `database.sql` file into your database
   ```bash
   mysql -u root -p phpblog < database.sql
   ```

3. **Configure Database Connection**:
   - Open `config.php`
   - Update the database credentials:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'your_username');
   define('DB_PASS', 'your_password');
   define('DB_NAME', 'phpblog');
   ```

4. **Set Permissions**:
   - Make sure the `uploads/` directory is writable:
   ```bash
   chmod 755 uploads/
   ```

5. **Access Your Site**:
   - Frontend: `http://localhost/phpblog/`
   - Admin Panel: `http://localhost/phpblog/admin/`

## Default Login Credentials

- **Username**: admin
- **Password**: admin123

**Important**: Change these credentials after first login!

## Usage

### Admin Panel

Access the admin panel at `/admin/login.php` and use the default credentials to login.

**Features**:
- **Dashboard**: Overview of your content
- **Posts**: View, create, edit, and delete blog posts
- **New Post**: Create new blog posts with rich content
- **Categories**: Manage post categories
- **View Site**: Preview your public website

### Creating Posts

1. Login to admin panel
2. Click on "New Post" in the navigation
3. Fill in the post details:
   - Title (required)
   - Content (supports HTML formatting)
   - Category (optional)
   - Featured Image (optional)
   - Status (Draft or Published)
4. Click "Create Post"

### Managing Categories

1. Navigate to "Categories" in admin panel
2. Enter category name and description
3. Click "Add Category"

## File Structure

```
phpblog/
├── admin/              # Admin panel files
│   ├── index.php       # Dashboard
│   ├── login.php       # Login page
│   ├── posts.php       # Manage posts
│   ├── post-create.php # Create new post
│   ├── categories.php  # Manage categories
│   └── logout.php      # Logout
├── assets/             # Static assets
│   ├── css/           # Stylesheets
│   ├── js/            # JavaScript files
│   └── images/        # Images
├── includes/           # PHP classes and functions
│   ├── Database.php   # Database connection
│   ├── Post.php       # Post model
│   ├── User.php       # User model
│   ├── Category.php   # Category model
│   └── functions.php  # Helper functions
├── templates/          # Template files
│   ├── header.php     # Header template
│   └── footer.php     # Footer template
├── uploads/           # Uploaded files
├── config.php         # Configuration file
├── database.sql       # Database schema
├── index.php          # Homepage
├── post.php           # Single post view
├── category.php       # Category posts view
└── README.md          # Documentation
```

## Security Features

- Password hashing with PHP's password_hash()
- PDO prepared statements for SQL injection prevention
- Input sanitization
- Session-based authentication
- File upload validation

## Customization

### Changing Site Name and Description

Edit `config.php`:
```php
define('SITE_NAME', 'Your Site Name');
define('SITE_DESCRIPTION', 'Your Site Description');
```

### Modifying Styles

Edit `assets/css/style.css` to customize the appearance.

### Changing Colors

The default theme uses gradient colors. To change:
- Primary gradient: `#667eea` to `#764ba2`
- Edit the CSS variables in `style.css`

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Opera (latest)
- Mobile browsers

## License

This project is open source and available for personal and commercial use.

## Support

For issues and questions, please open an issue on GitHub.

## Credits

Developed by ZelTroN-2k3

---

**phpBlog** - Clean, Simple, Lightweight, Responsive & User-Friendly CMS
