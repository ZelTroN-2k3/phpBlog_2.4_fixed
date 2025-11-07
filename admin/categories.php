<?php
session_start();
require_once '../config.php';
require_once '../includes/Database.php';
require_once '../includes/Category.php';
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$database = new Database();
$db = $database->connect();
$category = new Category($db);

$categories = $category->read();

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category->name = sanitize($_POST['name']);
    $category->slug = createSlug($_POST['name']);
    $category->description = sanitize($_POST['description']);
    
    if ($category->create()) {
        $success = true;
        $categories = $category->read(); // Refresh list
    } else {
        $error = 'Failed to create category';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories - <?php echo SITE_NAME; ?></title>
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
                    <li><a href="posts.php">Posts</a></li>
                    <li><a href="post-create.php">New Post</a></li>
                    <li><a href="categories.php" class="active">Categories</a></li>
                    <li><a href="../index.php" target="_blank">View Site</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>
        
        <main class="admin-main">
            <div class="admin-header">
                <h1>Manage Categories</h1>
            </div>
            
            <div class="admin-content">
                <div class="categories-container">
                    <div class="category-form">
                        <h2>Add New Category</h2>
                        
                        <?php if ($success): ?>
                            <div class="success-message">Category created successfully!</div>
                        <?php endif; ?>
                        
                        <?php if ($error): ?>
                            <div class="error-message"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="form-group">
                                <label for="name">Name *</label>
                                <input type="text" id="name" name="name" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea id="description" name="description" rows="3"></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Add Category</button>
                        </form>
                    </div>
                    
                    <div class="categories-list">
                        <h2>Existing Categories</h2>
                        <table>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($categories->rowCount() > 0): ?>
                                    <?php while ($cat = $categories->fetch()): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($cat['name']); ?></td>
                                            <td><?php echo htmlspecialchars($cat['slug']); ?></td>
                                            <td><?php echo htmlspecialchars($cat['description'] ?: ''); ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center">No categories found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
