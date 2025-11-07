# Contributing to phpBlog

Thank you for your interest in contributing to phpBlog! This document provides guidelines for contributing to the project.

## Code of Conduct

Be respectful and constructive in all interactions with the project and its community.

## How to Contribute

### Reporting Bugs

If you find a bug, please open an issue on GitHub with:
- A clear title and description
- Steps to reproduce the issue
- Expected behavior vs actual behavior
- Your environment (PHP version, MySQL version, OS)
- Screenshots if applicable

### Suggesting Features

We welcome feature suggestions! Please:
- Check if the feature has already been suggested
- Provide a clear use case
- Explain how it benefits users
- Consider implementation complexity

### Code Contributions

#### Getting Started

1. Fork the repository
2. Clone your fork: `git clone https://github.com/YOUR-USERNAME/phpBlog_2.4_fixed.git`
3. Create a branch: `git checkout -b feature/your-feature-name`
4. Make your changes
5. Test thoroughly
6. Commit with clear messages
7. Push to your fork
8. Open a Pull Request

#### Coding Standards

- Follow PSR-1 and PSR-2 coding standards
- Use meaningful variable and function names
- Comment complex logic
- Keep functions focused and small
- Maintain consistent indentation (4 spaces)
- Use PDO prepared statements for all database queries
- Sanitize all user input
- Validate file uploads properly

#### PHP Style Guide

```php
<?php
// Use camelCase for variables and functions
$userName = 'John';

// Use PascalCase for classes
class UserManager {
    // Use descriptive names
    public function createUser($username, $email) {
        // Implementation
    }
}

// Always use braces, even for single-line statements
if ($condition) {
    doSomething();
}

// Use PDO for database operations
$stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
$stmt->bindParam(':id', $userId);
$stmt->execute();
?>
```

#### Database Changes

If your contribution requires database changes:
- Update the `database.sql` file
- Provide migration instructions
- Test on fresh installation
- Document in CHANGELOG.md

#### Security

- Never commit passwords or API keys
- Use prepared statements for SQL
- Validate and sanitize all input
- Escape output appropriately
- Follow OWASP security guidelines

#### Testing

Before submitting:
- Test on PHP 7.0, 7.4, and 8.0+
- Test on MySQL 5.6 and 8.0
- Verify responsive design on mobile
- Check for PHP syntax errors: `php -l file.php`
- Test all CRUD operations
- Verify security measures work

### Documentation

Good documentation helps everyone:
- Update README.md if needed
- Add inline comments for complex code
- Update INSTALL.md for setup changes
- Document new features in CHANGELOG.md

### Commit Messages

Write clear commit messages:

```
Add user profile page

- Create profile.php with user information display
- Add edit profile functionality
- Implement avatar upload
- Update navigation to include profile link
```

Good commit message format:
```
<type>: <short summary>

<detailed description>

<related issues>
```

Types: feat, fix, docs, style, refactor, test, chore

### Pull Request Process

1. Update documentation
2. Add yourself to contributors if you'd like
3. Ensure all tests pass
4. Link related issues
5. Describe your changes clearly
6. Wait for review
7. Address feedback

### Review Process

Maintainers will:
- Review code quality
- Check security implications
- Verify functionality
- Test on different environments
- Provide constructive feedback

Please be patient during reviews. We aim to respond within 7 days.

## Development Setup

```bash
# Clone repository
git clone https://github.com/ZelTroN-2k3/phpBlog_2.4_fixed.git
cd phpBlog_2.4_fixed

# Set up database
mysql -u root -p < database.sql

# Configure
cp config.php config.php
# Edit config.php with your settings

# Start development server
php -S localhost:8000
```

## Project Structure

```
phpblog/
├── admin/              # Admin panel
├── assets/             # CSS, JS, images
├── includes/           # PHP classes
├── templates/          # Template files
├── uploads/            # User uploads
├── config.php          # Configuration
└── database.sql        # Database schema
```

## Questions?

Feel free to:
- Open an issue for questions
- Join discussions on GitHub
- Email the maintainers

Thank you for contributing to phpBlog! 🚀
