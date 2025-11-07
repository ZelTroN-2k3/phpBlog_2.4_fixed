<?php
session_start();
require_once 'config.php';
require_once 'includes/Database.php';
require_once 'includes/Post.php';
require_once 'includes/Category.php';
require_once 'includes/functions.php';

// Get category ID
if (!isset($_GET['id'])) {
    redirect('index.php');
}

$category_id = (int)$_GET['id'];

// Initialize database
$database = new Database();
$db = $database->connect();

// Initialize models
$post = new Post($db);
$category = new Category($db);

// Get categories for navigation
$categories = $category->read();

// Get category posts using the new method
$posts = $post->readByCategory($category_id);

// Get category name
$cat_query = "SELECT name FROM categories WHERE id = :id";
$cat_stmt = $db->prepare($cat_query);
$cat_stmt->bindParam(':id', $category_id);
$cat_stmt->execute();
$category_info = $cat_stmt->fetch();

if (!$category_info) {
    redirect('index.php');
}

include 'templates/header.php';
?>

<main>
    <div class="container">
        <div class="content-area">
            <div class="posts">
                <h1 style="margin-bottom: 30px; color: #667eea;">
                    Category: <?php echo htmlspecialchars($category_info['name']); ?>
                </h1>
                
                <?php if ($posts->rowCount() > 0): ?>
                    <?php while ($row = $posts->fetch()): ?>
                        <article class="post">
                            <?php if ($row['featured_image']): ?>
                                <div class="post-image">
                                    <img src="uploads/<?php echo htmlspecialchars($row['featured_image']); ?>" 
                                         alt="<?php echo htmlspecialchars($row['title']); ?>">
                                </div>
                            <?php endif; ?>
                            <div class="post-content">
                                <h2><a href="post.php?id=<?php echo $row['id']; ?>">
                                    <?php echo htmlspecialchars($row['title']); ?>
                                </a></h2>
                                <div class="post-meta">
                                    <span class="author">By <?php echo htmlspecialchars($row['author_name']); ?></span>
                                    <span class="date"><?php echo formatDate($row['created_at']); ?></span>
                                </div>
                                <div class="post-excerpt">
                                    <?php echo excerpt(strip_tags($row['content']), 200); ?>
                                </div>
                                <a href="post.php?id=<?php echo $row['id']; ?>" class="read-more">Read More</a>
                            </div>
                        </article>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-posts">
                        <h2>No posts in this category</h2>
                        <p><a href="index.php">Back to Home</a></p>
                    </div>
                <?php endif; ?>
            </div>
            
            <aside class="sidebar">
                <div class="widget">
                    <h3>About <?php echo SITE_NAME; ?></h3>
                    <p><?php echo SITE_DESCRIPTION; ?></p>
                </div>
                
                <div class="widget">
                    <h3>Categories</h3>
                    <ul>
                        <?php 
                        $categories_sidebar = $category->read();
                        while ($cat = $categories_sidebar->fetch()): 
                        ?>
                            <li><a href="category.php?id=<?php echo $cat['id']; ?>">
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </a></li>
                        <?php endwhile; ?>
                    </ul>
                </div>
            </aside>
        </div>
    </div>
</main>

<?php include 'templates/footer.php'; ?>
