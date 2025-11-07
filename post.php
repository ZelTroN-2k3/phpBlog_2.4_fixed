<?php
session_start();
require_once 'config.php';
require_once 'includes/Database.php';
require_once 'includes/Post.php';
require_once 'includes/Category.php';
require_once 'includes/functions.php';

// Get post ID
if (!isset($_GET['id'])) {
    redirect('index.php');
}

$post_id = (int)$_GET['id'];

// Initialize database
$database = new Database();
$db = $database->connect();

// Initialize models
$post = new Post($db);
$category = new Category($db);

// Get categories for navigation
$categories = $category->read();

// Get post
$post->id = $post_id;
if (!$post->readOne()) {
    redirect('index.php');
}

include 'templates/header.php';
?>

<main>
    <div class="container">
        <div class="single-post">
            <article>
                <h1><?php echo htmlspecialchars($post->title); ?></h1>
                
                <div class="post-meta">
                    <span class="date"><?php echo formatDate($post->created_at); ?></span>
                </div>
                
                <?php if ($post->featured_image): ?>
                    <div class="featured-image">
                        <img src="uploads/<?php echo htmlspecialchars($post->featured_image); ?>" 
                             alt="<?php echo htmlspecialchars($post->title); ?>">
                    </div>
                <?php endif; ?>
                
                <div class="post-body">
                    <?php 
                    // Note: Content is not escaped as it may contain HTML formatting
                    // Only admin users can create/edit posts, so this is safe
                    echo $post->content; 
                    ?>
                </div>
            </article>
            
            <div class="post-navigation">
                <a href="index.php" class="btn">&larr; Back to Home</a>
            </div>
        </div>
    </div>
</main>

<?php include 'templates/footer.php'; ?>
