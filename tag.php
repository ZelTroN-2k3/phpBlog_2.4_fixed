<?php
include "core.php";
head();

if ($settings['sidebar_position'] == 'Left') {
	sidebar();
}
?>
    <div class="col-md-8">
<?php
$slug = $_GET['name']; // Récupère le slug du tag depuis l'URL

if (empty($slug)) {
    echo '<meta http-equiv="refresh" content="0; url=blog">';
    exit;
}

// MODIFICATION 1: On cherche le TAG (au lieu de la catégorie)
$run_tag = mysqli_query($connect, "SELECT * FROM `tags` WHERE tag_slug='$slug' LIMIT 1");
if (mysqli_num_rows($run_tag) == 0) {
    // Si le tag n'existe pas, on redirige vers le blog
    echo '<meta http-equiv="refresh" content="0; url=blog">';
    exit;
}
$row_tag = mysqli_fetch_assoc($run_tag);
$tag_id  = $row_tag['tag_id']; // On récupère l'ID du tag

// MODIFICATION 2: On affiche le titre du TAG
echo '
                    <div class="card shadow-sm bg-light">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-tag"></i> Tag: <b>' . htmlspecialchars($row_tag['tag_name']) . '</b></h5>
                        </div>
                    </div>
';

// MODIFICATION 3: On récupère les articles via la table de liaison (JOIN)
$run = mysqli_query($connect, "
    SELECT p.* FROM `posts` p
    INNER JOIN `post_tags` pt ON p.id = pt.post_id
    WHERE pt.tag_id = '$tag_id' AND p.active = 'Yes' 
    ORDER BY p.id DESC
");

$count = mysqli_num_rows($run);
if ($count <= 0) {
    echo '
                    <div class="card shadow-sm bg-light mt-3">
                        <div class="card-body">
                            <div class="alert alert-info">Il n\'y a aucun article avec ce mot-clé.</div>
                        </div>
                    </div>
';
} else {
    while ($row = mysqli_fetch_assoc($run)) {
        $post_id    = $row['id'];
        $image      = $row['image'];
        $author_id  = $row['author_id'];
        $category_id = $row['category_id'];
        
        // La boucle d'affichage est identique à category.php
        echo '
                    <div class="card shadow-sm bg-light mt-3">
                        <div class="row">
        ';
        if ($image != '') {
            echo '
                            <div class="col-md-5">
                                <a href="post?name=' . $row['slug'] . '">
                                    <img src="' . $image . '" class="card-img-top" alt="' . $row['title'] . '">
                                </a>
                            </div>
        ';
        }
        echo '
                            <div class="';
        if ($image != '') {
            echo 'col-md-7';
        } else {
            echo 'col-md-12';
        }
        echo '">
                                <div class="card-body">
                                    <div class="mb-1">
                                        <i class="fas fa-chevron-right"></i> <a href="category?name=' . post_categoryslug($category_id) . '">' . post_category($category_id) . '</a>
                                    </div>
                                    <h5 class="card-title fw-bold"><a href="post?name=' . $row['slug'] . '">' . $row['title'] . '</a></h5>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small>
                                            Posted by <b><i><i class="fas fa-user"></i> ' . post_author($author_id) . '</i></b> 
                                            on <b><i><i class="far fa-calendar-alt"></i> ' . date($settings['date_format'], strtotime($row['date'])) . ', ' . $row['time'] . '</i></b>
                                        </small>
                                        <small class="float-end">
                                            <i class="fa fa-comments"></i> <a href="post?name=' . $row['slug'] . '#comments"><b>' . post_commentscount($post_id) . '</b></a>
                                        </small>
                                    </div>
                                    <hr>
                                    ' . short_text(html_entity_decode($row['content']), 200) . '
                                    <div class="mt-3">
                                        <a href="post?name=' . $row['slug'] . '" class="btn btn-primary">
                                            <i class="fas fa-book-open"></i> Lire la suite
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
        ';
    }
}
?>
    </div>
<?php
if ($settings['sidebar_position'] == 'Right') {
	sidebar();
}

footer();
?>