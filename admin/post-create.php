<?php
session_start();
require_once '../config.php';
require_once '../includes/Database.php';
require_once '../includes/Post.php';
require_once '../includes/Category.php';
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$database = new Database();
$db = $database->connect();
$post = new Post($db);
$category = new Category($db);

$categories = $category->read();

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post->title = sanitize($_POST['title']);
    $post->content = $_POST['content']; // Allow HTML in content
    $post->author_id = $_SESSION['user_id'];
    $post->category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
    $post->status = sanitize($_POST['status']);
    $post->featured_image = '';
    
    // Handle image upload
    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === 0) {
        $upload_result = uploadImage($_FILES['featured_image']);
        if ($upload_result['success']) {
            $post->featured_image = $upload_result['filename'];
        } else {
            $error = $upload_result['message'];
        }
    }
    
    if (empty($error)) {
        if ($post->create()) {
            $success = true;
        } else {
            $error = 'Failed to create post';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post - <?php echo SITE_NAME; ?></title>
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
                    <li><a href="post-create.php" class="active">New Post</a></li>
                    <li><a href="categories.php">Categories</a></li>
                    <li><a href="../index.php" target="_blank">View Site</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>
        
        <main class="admin-main">
            <div class="admin-header">
                <h1>Create New Post</h1>
            </div>
            
            <div class="admin-content">
                <?php if ($success): ?>
                    <div class="success-message">Post created successfully! <a href="posts.php">View all posts</a></div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="error-message"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST" enctype="multipart/form-data" class="post-form">
                    <div class="form-group">
                        <label for="title">Title *</label>
                        <input type="text" id="title" name="title" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="content">Content *</label>
                        <textarea id="content" name="content" rows="15" required></textarea>
                        <p class="help-text">You can use HTML tags for formatting</p>
                    </div>
                    
                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select id="category_id" name="category_id">
                            <option value="">Select Category</option>
                            <?php while ($cat = $categories->fetch()): ?>
                                <option value="<?php echo $cat['id']; ?>">
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="featured_image">Featured Image</label>
                        <input type="file" id="featured_image" name="featured_image" accept="image/*">
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Status *</label>
                        <select id="status" name="status" required>
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Create Post</button>
                        <a href="posts.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
