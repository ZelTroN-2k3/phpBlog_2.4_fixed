<?php
include "core.php";
head();

// DÉBUT DE L'AJOUT - Fonctions pour les commentaires imbriqués

/**
 * Construit un arbre hiérarchique de commentaires.
 * @param array $comments Liste de tous les commentaires
 * @param int $parentId L'ID du parent à chercher
 * @return array Arbre hiérarchique
 */
function buildCommentsTree(array $comments, $parentId = 0) {
    $branch = [];
    foreach ($comments as $comment) {
        if ($comment['parent_id'] == $parentId) {
            $children = buildCommentsTree($comments, $comment['id']);
            if ($children) {
                $comment['children'] = $children;
            }
            $branch[] = $comment;
        }
    }
    return $branch;
}

/**
 * Affiche récursivement les commentaires et leurs réponses.
 * @param array $comments Arbre de commentaires (issu de buildCommentsTree)
 * @param bool $isChild Indique si c'est un sous-commentaire (pour l'indentation)
 */
function displayComments(array $comments, $isChild = false) {
    
    // On a besoin de $connect et $settings dans la fonction
    global $connect, $settings; 
    
    // Détermine la classe de conteneur (pour l'indentation)
    $containerClass = $isChild ? 'ms-4 mt-3' : 'mt-3';
    
    foreach ($comments as $comment) {
        
        // --- Logique d'auteur (copiée de l'ancienne boucle) ---
        $aauthor = $comment['user_id'];
        if ($comment['guest'] == 'Yes') {
            $aavatar = 'assets/img/avatar.png';
            $aauthor_name = htmlspecialchars($aauthor); // Nom du guest
            $arole   = '<span class="badge bg-secondary">Guest</span>';
        } else {
            $querych = mysqli_query($connect, "SELECT * FROM `users` WHERE id='$aauthor' LIMIT 1");
            if (mysqli_num_rows($querych) > 0) {
                $rowch = mysqli_fetch_assoc($querych);
                $aavatar = $rowch['avatar'];
                $aauthor_name = htmlspecialchars($rowch['username']);
                if ($rowch['role'] == 'Admin') {
                    $arole = '<span class="badge bg-danger">Administrator</span>';
                } elseif ($rowch['role'] == 'Editor') {
                    $arole = '<span class="badge bg-warning">Editor</span>';
                } else {
                    $arole = '<span class="badge bg-info">User</span>';
                }
            } else {
                // Fallback si l'utilisateur est supprimé
                $aavatar = 'assets/img/avatar.png';
                $aauthor_name = 'Utilisateur supprimé';
                $arole = '';
            }
        }
        // --- Fin de la logique d'auteur ---
        
        echo '
        <div class="' . $containerClass . '">
            <div class="row d-flex justify-content-center bg-white rounded border ms-1 me-1">
                <div class="mb-2 d-flex flex-start align-items-center">
                    <img class="rounded-circle shadow-1-strong mt-1 me-3"
                        src="' . $aavatar . '" alt="' . $aauthor_name . '" 
                        width="50" height="50" />
                    <div class="mt-1 mb-1">
                        <h6 class="fw-bold mt-1 mb-1">
                            <i class="fa fa-user"></i> ' . $aauthor_name . ' ' . $arole . '
                        </h6>
                        <p class="small mb-0">
                            <i><i class="fas fa-calendar"></i> ' . date($settings['date_format'], strtotime($comment['date'])) . ', ' . $comment['time'] . '</i>
                        </p>
                    </div>
                </div>
                <hr class="my-0" />
                <p class="mt-1 mb-1 pb-1">
                    ' . emoticons(htmlspecialchars($comment['comment'])) . '
                </p>
                
                <div class="mb-2 text-end">
                    <button class="btn btn-sm btn-primary reply-btn" 
                            data-comment-id="' . $comment['id'] . '" 
                            data-comment-author="' . htmlspecialchars($aauthor_name, ENT_QUOTES) . '">
                        <i class="fas fa-reply"></i> Répondre
                    </button>
                </div>
            </div>
        ';
        
        // Si ce commentaire a des enfants, on appelle récursivement la fonction
        if (!empty($comment['children'])) {
            displayComments($comment['children'], true);
        }
        
        echo '</div>'; // Ferme le conteneur
    }
}
// FIN DE L'AJOUT

if ($settings['sidebar_position'] == 'Left') {
	sidebar();
}
?>
    <div class="col-md-8 mb-3">
<?php
$slug = $_GET['name'];

if (empty($slug)) {
    echo '<meta http-equiv="refresh" content="0; url=blog">';
    exit;
}

$runq = mysqli_query($connect, "SELECT * FROM `posts` WHERE active='Yes' AND publish_datetime <= NOW() AND slug='$slug'");
if (mysqli_num_rows($runq) == 0) {
    echo '<meta http-equiv="refresh" content="0; url=blog">';
    exit;
}

mysqli_query($connect, "UPDATE `posts` SET views = views + 1 WHERE active='Yes' AND publish_datetime <= NOW() AND slug='$slug'");
$row         = mysqli_fetch_assoc($runq);
$post_id     = $row['id'];
$post_slug   = $row['slug'];

// DÉBUT DE L'AJOUT - Récupération et préparation des Tags

$tags_html_block = ''; // Initialise le bloc HTML complet
$tags_links = '';      // Initialise la chaîne de liens

// Requête SQL pour trouver les tags de ce post
$tags_sql = "
    SELECT t.tag_name, t.tag_slug 
    FROM `tags` t
    INNER JOIN `post_tags` pt ON t.tag_id = pt.tag_id
    WHERE pt.post_id = '$post_id'
    ORDER BY t.tag_name
";

$tags_query = mysqli_query($connect, $tags_sql);

if (mysqli_num_rows($tags_query) > 0) {
    while ($tag_row = mysqli_fetch_assoc($tags_query)) {
        // Construit le lien pour chaque tag
        // On utilise les classes "badge" de Bootstrap qui sont déjà utilisées dans votre code
        $tags_links .= ' <a href="tag.php?name=' . htmlspecialchars($tag_row['tag_slug']) . '" class="badge bg-secondary text-decoration-none me-1">
                            ' . htmlspecialchars($tag_row['tag_name']) . '
                        </a>';
    }
    
    // Construit le bloc HTML complet qui sera inséré dans le "echo" ci-dessous
    $tags_html_block = '
    <div class="mb-3">
        <h5 style="display: inline-block; margin-right: 5px;"><i class="fas fa-tags"></i> Tags:</h5>
        ' . $tags_links . '
    </div>
    <hr />
    ';
}
// FIN DE L'AJOUT

echo '
                    <div class="card shadow-sm bg-light">
                        <div class="col-md-12">
							';
if ($row['image'] != '') {
    echo '
        <img src="' . $row['image'] . '" width="100%" height="auto" alt="' . $row['title'] . '"/>
';
}
echo '
            <div class="card-body">
                
				<div class="mb-1">
					<i class="fas fa-chevron-right"></i> <a href="category?name=' . post_categoryslug($row['category_id']) . '">' . post_category($row['category_id']) . '</a>
				</div>
				
				<h5 class="card-title fw-bold">' . $row['title'] . '</h5>
				
				<div class="d-flex justify-content-between align-items-center">
					<small>
						Posted by <b><i><i class="fas fa-user"></i> ' . post_author($row['author_id']) . '</i></b> 
						on <b><i><i class="far fa-calendar-alt"></i> ' . date($settings['date_format'], strtotime($row['date'])) . ', ' . $row['time'] . '</i></b>
					</small>
					<small> 	
						<i class="fa fa-eye"></i> ' . $row['views'] . '
					</small>
					<small class="float-end">
						<i class="fa fa-comments"></i> <a href="#comments"><b>' . post_commentscount($row['id']) . '</b></a>
					</small>
				</div>
				<hr />
				
                ' . html_entity_decode($row['content']) . '
				<hr />
				' . $tags_html_block . '
				<h5><i class="fas fa-share-alt-square"></i> Share</h5>
				<div id="share" style="font-size: 14px;"></div>
				<hr />

				<h5 class="mt-2" id="comments">
					<i class="fa fa-comments"></i> Comments (' . post_commentscount($row['id']) . ')
				</h5>
';
?>

<?php
// DÉBUT DE LA MODIFICATION - Logique d'affichage des commentaires

// 1. Récupérer TOUS les commentaires approuvés pour ce post, triés par ID (important pour la hiérarchie)
$all_comments_query = mysqli_query($connect, "SELECT * FROM comments WHERE post_id='$row[id]' AND approved='Yes' ORDER BY id ASC");
$comments_list = [];
while ($comment = mysqli_fetch_assoc($all_comments_query)) {
    $comments_list[] = $comment;
}

$count = count($comments_list);
if ($count <= 0) {
    echo '<div class="alert alert-info">There are no comments yet.</div>';
} else {
    // 2. Construire l'arbre hiérarchique
    $comments_tree = buildCommentsTree($comments_list);
    
    // 3. Appeler la fonction d'affichage récursive
    displayComments($comments_tree);
}

// FIN DE LA MODIFICATION
?>
<div id="comment_form_container">
                    <h5 class="mt-4" id="comment-form-title">Leave A Comment</h5>


<?php
$guest = 'No';

if ($logged == 'No' AND $settings['comments'] == 'guests') {
    $cancomment = 'Yes';
} else {
    $cancomment = 'No';
}
if ($logged == 'Yes') {
    $cancomment = 'Yes';
}

if ($cancomment == 'Yes') {
?>
<form name="comment_form" action="post?name=<?php
    echo $post_slug;
?>" method="post">
                        
    <input type="hidden" name="parent_id" id="comment_parent_id" value="0">
    
    <div id="reply-to-container" class="mb-2" style="display:none;">
        <span class="badge bg-primary">En réponse à <span id="reply-to-name"></span></span>
        <a href="#comments" id="cancel-reply-link" class="btn btn-sm btn-danger ms-2">Annuler</a>
    </div>
    <?php
    if ($logged == 'No') {
        $guest = 'Yes';
?>
                        <label for="name"><i class="fa fa-user"></i> Name:</label>
                        <input type="text" name="author" value="" class="form-control" required />
                        <br />
<?php
    }
?>
                        <label for="comment"><i class="fa fa-comment"></i> Comment:</label>
                        <textarea name="comment" id="comment" rows="5" class="form-control" maxlength="1000" oninput="countText()" required></textarea>
						<label for="characters"><i>Characters left: </i></label>
						<span id="characters">1000</span><br>
						<br />
<?php
    if ($logged == 'No') {
        $guest = 'Yes';
?>
						<center><div class="g-recaptcha" data-sitekey="<?php
        echo $settings['gcaptcha_sitekey'];
?>"></div></center>
<?php
    }
?>
                        <input type="submit" name="post" class="btn btn-primary col-12" value="Post" />
            </form>
<?php
} else {
    echo '<div class="alert alert-info">Please <strong><a href="login"><i class="fas fa-sign-in-alt"></i> Sign In</a></strong> to be able to post a comment.</div>';
}

if ($cancomment == 'Yes') {
    if (isset($_POST['post'])) {
        
        // DÉBUT DE L'AJOUT
        $parent_id = (int)($_POST['parent_id'] ?? 0);
        // FIN DE L'AJOUT
        
        $authname_problem = 'No';
        $date             = date($settings['date_format']);
        $time             = date('H:i');
		$comment          = $_POST['comment'];
		
		$captcha = '';
		
        if ($logged == 'No') {
            $author = $_POST['author'];
            
            $bot = 'Yes';
            if (isset($_POST['g-recaptcha-response'])) {
                $captcha = $_POST['g-recaptcha-response'];
            }
            if ($captcha) {
                $url          = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($settings['gcaptcha_secretkey']) . '&response=' . urlencode($captcha);
                $response     = file_get_contents($url);
                $responseKeys = json_decode($response, true);
                if ($responseKeys["success"]) {
                    $bot = 'No';
                }
            }
            
            if (strlen($author) < 2) {
                $authname_problem = 'Yes';
                echo '<div class="alert alert-warning">Your name is too short.</div>';
            }
        } else {
            $bot    = 'No';
            $author = $rowu['id'];
        }
        
        if (strlen($comment) < 2) {
            echo '<div class="alert alert-danger">Your comment is too short.</div>';
        } else {
            if ($authname_problem == 'No' AND $bot == 'No') {
                $runq = mysqli_query($connect, "INSERT INTO `comments` (`post_id`, `parent_id`, `comment`, `user_id`, `date`, `time`, `guest`) VALUES ('$row[id]', '$parent_id', '$comment', '$author', '$date', '$time', '$guest')");
                echo '<div class="alert alert-success">Your comment has been successfully posted</div>';
                echo '<meta http-equiv="refresh" content="0;url=post?name=' . $row['slug'] . '#comments">';
            }
        }
    }
}
?>
                </div> </div>
                </div>
            </div>
        </div>
		
<script>
$("#share").jsSocials({
    showCount: false,
    showLabel: true,
    shares: [
        { share: "facebook", logo: "fab fa-facebook-square", label: "Share" },
        { share: "twitter", logo: "fab fa-twitter-square", label: "Tweet" },       
        { share: "whatsapp", logo: "fab fa-whatsapp", label: "WhatsApp" },
        { share: "telegram", logo: "fab fa-telegram", label: "Telegram" },        
        { share: "linkedin", logo: "fab fa-linkedin", label: "Share" },
		{ share: "email", logo: "fas fa-envelope", label: "E-Mail" }
    ]
});

function countText() {
// ... la suite de votre script
	let text = document.comment_form.comment.value;
	
	document.getElementById('characters').innerText = 1000 - text.length;
	//document.getElementById('words').innerText = text.length == 0 ? 0 : text.split(/\s+/).length;
	//document.getElementById('rows').innerText = text.length == 0 ? 0 : text.split(/\n/).length;
}

// DÉBUT DE L'AJOUT - JS pour les réponses aux commentaires

// Quand on clique sur un bouton "Répondre"
$(document).on('click', '.reply-btn', function() {
    // Récupère les infos du bouton
    var commentId = $(this).data('comment-id');
    var commentAuthor = $(this).data('comment-author');
    
    // Met à jour le champ caché du formulaire
    $('#comment_parent_id').val(commentId);
    
    // Affiche le conteneur "Réponse à"
    $('#reply-to-name').text(commentAuthor);
    $('#reply-to-container').show();
    
    // Change le titre du formulaire
    $('#comment-form-title').text('Votre réponse à ' + commentAuthor);
    
    // Scrolle l'utilisateur vers le formulaire
    $('html, body').animate({
        scrollTop: $('#comment_form_container').offset().top - 50 // -50 pour un peu d'espace
    }, 500);
});

// Quand on clique sur "Annuler"
$('#cancel-reply-link').on('click', function(e) {
    e.preventDefault(); // Empêche le lien de sauter
    
    // Réinitialise le champ caché
    $('#comment_parent_id').val(0);
    
    // Cache le conteneur "Réponse à"
    $('#reply-to-container').hide();
    
    // Réinitialise le titre du formulaire
    $('#comment-form-title').text('Leave A Comment');
    
    // Scrolle l'utilisateur vers le formulaire
    $('html, body').animate({
        scrollTop: $('#comment_form_container').offset().top - 50
    }, 500);
});

// FIN DE L'AJOUT
</script>
<?php
if ($settings['sidebar_position'] == 'Right') {
	sidebar();
}
footer();
?>