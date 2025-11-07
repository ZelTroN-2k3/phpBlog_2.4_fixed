<?php
session_start();
require_once 'config.php';
require_once 'includes/Database.php';
require_once 'includes/Post.php';
require_once 'includes/Category.php';
require_once 'includes/functions.php';

// Initialize database
$database = new Database();
$db = $database->connect();

// Initialize models
$post = new Post($db);
$category = new Category($db);

// Get categories for navigation
$categories = $category->read();

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * POSTS_PER_PAGE;

// Get posts
$posts = $post->read(POSTS_PER_PAGE, $offset);
$total_posts = $post->count();
$total_pages = ceil($total_posts / POSTS_PER_PAGE);

include 'templates/header.php';
?>

<main>
    <div class="container">
        <div class="content-area">
            <div class="posts">
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
                                    <?php if ($row['category_name']): ?>
                                        <span class="category">
                                            <a href="category.php?id=<?php echo $row['category_id']; ?>">
                                                <?php echo htmlspecialchars($row['category_name']); ?>
                                            </a>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="post-excerpt">
                                    <?php echo excerpt(strip_tags($row['content']), 200); ?>
                                </div>
                                <a href="post.php?id=<?php echo $row['id']; ?>" class="read-more">Read More</a>
                            </div>
                        </article>
                    <?php endwhile; ?>
                    
                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                        <div class="pagination">
                            <?php if ($page > 1): ?>
                                <a href="?page=<?php echo $page - 1; ?>">&laquo; Previous</a>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <a href="?page=<?php echo $i; ?>" 
                                   class="<?php echo $i == $page ? 'active' : ''; ?>">
                                    <?php echo $i; ?>
                                </a>
                            <?php endfor; ?>
                            
                            <?php if ($page < $total_pages): ?>
                                <a href="?page=<?php echo $page + 1; ?>">Next &raquo;</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="no-posts">
                        <h2>No posts found</h2>
                        <p>Please check back later for new content.</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <aside class="sidebar">
                <div class="widget">
                    <h3>About <?php echo SITE_NAME; ?></h3>
                    <p><?php echo SITE_DESCRIPTION; ?></p>
                    <p>Clean, simple, lightweight, responsive and user-friendly CMS for blogs, portals, magazines, and more.</p>
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
