<?php
// phpBlog Configuration File
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'phpblog');

// Site Configuration
define('SITE_NAME', 'phpBlog');
define('SITE_URL', 'http://localhost/phpblog');
define('SITE_DESCRIPTION', 'A Multi-Purpose CMS - News, Blog & Magazine');

// Admin Configuration
define('ADMIN_EMAIL', 'admin@phpblog.com');

// Security
define('SECURE_KEY', 'phpblog_secure_key_2024');

// Upload Configuration
define('UPLOAD_DIR', __DIR__ . '/uploads/');
define('MAX_FILE_SIZE', 5242880); // 5MB

// Pagination
define('POSTS_PER_PAGE', 10);

// Timezone
date_default_timezone_set('UTC');
?>
