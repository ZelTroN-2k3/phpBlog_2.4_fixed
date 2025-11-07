<?php
session_start();
require_once '../config.php';
require_once '../includes/Database.php';
require_once '../includes/Post.php';
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$database = new Database();
$db = $database->connect();
$post = new Post($db);

// Get all posts (including drafts for admin)
$query = "SELECT p.*, u.username as author_name, c.name as category_name 
          FROM posts p
          LEFT JOIN users u ON p.author_id = u.id
          LEFT JOIN categories c ON p.category_id = c.id
          ORDER BY p.created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$posts = $stmt;

// Handle delete
if (isset($_GET['delete'])) {
    $post->id = (int)$_GET['delete'];
    if ($post->delete()) {
        redirect('posts.php?deleted=1');
    }
}

$deleted = isset($_GET['deleted']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Posts - <?php echo SITE_NAME; ?></title>
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
                    <li><a href="index.php">Dashboard</a></li>
                    <li><a href="posts.php" class="active">Posts</a></li>
                    <li><a href="post-create.php">New Post</a></li>
                    <li><a href="categories.php">Categories</a></li>
                    <li><a href="../index.php" target="_blank">View Site</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>
        
        <main class="admin-main">
            <div class="admin-header">
                <h1>Manage Posts</h1>
                <a href="post-create.php" class="btn btn-primary">Create New Post</a>
            </div>
            
            <div class="admin-content">
                <?php if ($deleted): ?>
                    <div class="success-message">Post deleted successfully!</div>
                <?php endif; ?>
                
                <div class="posts-list">
                    <table>
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($posts->rowCount() > 0): ?>
                                <?php while ($row = $posts->fetch()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                                        <td><?php echo htmlspecialchars($row['author_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['category_name'] ?? 'None'); ?></td>
                                        <td>
                                            <span class="status-badge <?php echo $row['status']; ?>">
                                                <?php echo ucfirst($row['status']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo formatDate($row['created_at']); ?></td>
                                        <td>
                                            <a href="post-edit.php?id=<?php echo $row['id']; ?>" class="btn-small">Edit</a>
                                            <a href="?delete=<?php echo $row['id']; ?>" 
                                               class="btn-small btn-danger"
                                               onclick="return confirm('Are you sure?')">Delete</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">No posts found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
