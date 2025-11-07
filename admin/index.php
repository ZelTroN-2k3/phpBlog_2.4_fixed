<?php
session_start();
require_once '../config.php';
require_once '../includes/Database.php';
require_once '../includes/Post.php';
require_once '../includes/Category.php';
require_once '../includes/functions.php';

// Check if logged in
if (!isLoggedIn()) {
    redirect('login.php');
}

// Initialize database
$database = new Database();
$db = $database->connect();

// Get stats
$post = new Post($db);
$category = new Category($db);

$total_posts = $post->count();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="admin-page">
    <div class="admin-container">
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <h2><?php echo SITE_NAME; ?></h2>
                <p class="admin-user">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></p>
            </div>
            <nav class="admin-nav">
                <ul>
                    <li><a href="index.php" class="active">Dashboard</a></li>
                    <li><a href="posts.php">Posts</a></li>
                    <li><a href="post-create.php">New Post</a></li>
                    <li><a href="categories.php">Categories</a></li>
                    <li><a href="../index.php" target="_blank">View Site</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>
        
        <main class="admin-main">
            <div class="admin-header">
                <h1>Dashboard</h1>
            </div>
            
            <div class="admin-content">
                <div class="dashboard-stats">
                    <div class="stat-card">
                        <h3>Total Posts</h3>
                        <p class="stat-number"><?php echo $total_posts; ?></p>
                    </div>
                </div>
                
                <div class="welcome-message">
                    <h2>Welcome to phpBlog Admin Panel</h2>
                    <p>Manage your content using the navigation menu on the left.</p>
                    <ul>
                        <li><strong>Posts:</strong> View and manage all your blog posts</li>
                        <li><strong>New Post:</strong> Create new blog posts</li>
                        <li><strong>Categories:</strong> Organize your content with categories</li>
                        <li><strong>View Site:</strong> Preview your public website</li>
                    </ul>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
