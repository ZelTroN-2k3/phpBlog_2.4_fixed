<?php
include "header.php";

if (isset($_POST['add'])) {
    $title       = addslashes($_POST['title']);
	$slug        = generateSeoURL($title);
    $active      = addslashes($_POST['active']);
	$featured    = addslashes($_POST['featured']);
    $category_id = addslashes($_POST['category_id']);
    $tags_input  = addslashes($_POST['tags']); // Récupère la chaîne de tags    
    $content     = addslashes($_POST['content']);
    
// DÉBUT DE LA MODIFICATION - Logique de planification
    $publish_datetime_input = $_POST['publish_datetime']; // ex: 2025-11-10T14:30
    
    if (!empty($publish_datetime_input)) {
        // L'utilisateur a choisi une date future (ou spécifique)
        $publish_timestamp = strtotime($publish_datetime_input);
        
        // 1. Pour la nouvelle colonne DATETIME (pour le filtrage SQL)
        // ON AJOUTE LES GUILLEMETS pour la requête
        $publish_datetime_sql = "'" . date('Y-m-d H:i:s', $publish_timestamp) . "'";
        
        // 2. Pour les anciennes colonnes VARCHAR (pour l'affichage)
        $date = date($settings['date_format'], $publish_timestamp);
        $time = date('H:i', $publish_timestamp);
        
    } else {
        // L'utilisateur publie maintenant (comportement par défaut)
        $current_timestamp = time();
        
        // 1. Pour la nouvelle colonne DATETIME (pour le filtrage SQL)
        // ON UTILISE NOW() directement dans la requête
        $publish_datetime_sql = "NOW()";
        
        // 2. Pour les anciennes colonnes VARCHAR (pour l'affichage)
        $date = date($settings['date_format'], $current_timestamp);
        $time = date('H:i', $current_timestamp);
    }
    // FIN DE LA MODIFICATION
    
	$author     = $uname;
	$auth_query = mysqli_query($connect, "SELECT id FROM `users` WHERE username = '$author' LIMIT 1");
    $auth       = mysqli_fetch_assoc($auth_query);
    $author_id  = $auth['id'];

    $image = '';
    
    if (@$_FILES['image']['name'] != '') {
        $target_dir    = "uploads/posts/";
        $target_file   = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        $uploadOk = 1;
        
        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo '<div class="alert alert-danger">The file is not an image.</div>';
            $uploadOk = 0;
        }
        
        // Check file size
        if ($_FILES["image"]["size"] > 10000000) {
            echo '<div class="alert alert-warning">Sorry, your file is too large.</div>';
            $uploadOk = 0;
        }
        
        if ($uploadOk == 1) {
            $string     = "0123456789wsderfgtyhjuk";
            $new_string = str_shuffle($string);
            $location   = "../uploads/posts/image_$new_string.$imageFileType";
            move_uploaded_file($_FILES["image"]["tmp_name"], $location);
            $image = 'uploads/posts/image_' . $new_string . '.' . $imageFileType . '';
	   }
    }
    
$add_sql = mysqli_query($connect, "INSERT INTO `posts` (category_id, title, slug, author_id, publish_datetime, image, content, date, time, active, featured) 
									   VALUES ('$category_id', '$title', '$slug', '$author_id', $publish_datetime_sql, '$image', '$content', '$date', '$time', '$active', '$featured')");

// DÉBUT DE L'AJOUT - Logique des Tags
    
    // 1. Récupérer l'ID du post qui vient d'être créé
    $new_post_id = mysqli_insert_id($connect);
    
    // 2. Traiter la chaîne de tags s'il y en a une
    if (!empty($tags_input)) {
        
        // Sépare la chaîne en un tableau (ex: "php, cms" -> ['php', 'cms'])
        $tags_array = explode(',', $tags_input);
        
        foreach ($tags_array as $tag_name) {
            
            // Nettoyer le nom du tag (supprimer les espaces avant/après)
            $tag_name = trim($tag_name);
            
            if (!empty($tag_name)) {
                
                // Créer un slug pour le tag (ex: "PHP Facile" -> "php-facile")
                // On utilise la fonction generateSeoURL qui existe déjà dans ce fichier
                $tag_slug = generateSeoURL($tag_name);
                
                // 3. Vérifier si le tag existe déjà dans la table `tags`
                $check_tag_sql = "SELECT tag_id FROM `tags` WHERE tag_slug = '$tag_slug' LIMIT 1";
                $check_tag_query = mysqli_query($connect, $check_tag_sql);
                
                if (mysqli_num_rows($check_tag_query) > 0) {
                    // Le tag existe : on récupère son ID
                    $existing_tag = mysqli_fetch_assoc($check_tag_query);
                    $tag_id = $existing_tag['tag_id'];
                } else {
                    // Le tag n'existe pas : on l'insère dans la table `tags`
                    $insert_tag_sql = "INSERT INTO `tags` (tag_name, tag_slug) VALUES ('$tag_name', '$tag_slug')";
                    mysqli_query($connect, $insert_tag_sql);
                    
                    // On récupère le nouvel ID créé
                    $tag_id = mysqli_insert_id($connect);
                }
                
                // 4. Insérer l'association dans la table pivot `post_tags`
                if ($new_post_id > 0 && $tag_id > 0) {
                    $insert_post_tag_sql = "INSERT INTO `post_tags` (post_id, tag_id) VALUES ('$new_post_id', '$tag_id')";
                    mysqli_query($connect, $insert_post_tag_sql);
                }
            }
        }
    }
    // FIN DE L'AJOUT - Logique des Tags

    $from     = $settings['email'];
    $sitename = $settings['sitename'];
	
    //$run3 = mysqli_query($connect, "SELECT * FROM `posts` WHERE title='$title'");
    //$row3 = mysqli_fetch_assoc($run3);
    //$id3  = $row3['id'];
	$id3 = $new_post_id;
    
    $run2 = mysqli_query($connect, "SELECT * FROM `newsletter`");
    while ($row = mysqli_fetch_assoc($run2)) {

        $to = $row['email'];
        $subject = $title;
        $message = '
<html>
<body>
  <b><h1>' . $settings['sitename'] . '</h1><b/>
  <h2>New post: <b><a href="' . $settings['site_url'] . '/post.php?id=' . $id3 . '" title="Read more">' . $title . '</a></b></h2><br />

  ' . html_entity_decode($content) . '
  
  <hr />
  <i>If you do not want to receive more notifications, you can <a href="' . $settings['site_url'] . '/unsubscribe?email=' . $to . '">Unsubscribe</a></i>
</body>
</html>
';
        
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

        $headers .= 'From: ' . $from . '';
        
        @mail($to, $subject, $message, $headers);
    }
    
    echo '<meta http-equiv="refresh" content="0;url=posts.php">';
}
?>
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h3 class="h3"><i class="fas fa-list"></i> Posts</h3>
	</div> 

    <div class="card">
        <h6 class="card-header">Add Post</h6>         
            <div class="card-body">
                <form name="post_form" action="" method="post" enctype="multipart/form-data">
					<p>
						<label>Title</label>
						<input class="form-control" name="title" id="title" value="" type="text" oninput="countText()" required>
						<i>For best SEO keep title under 50 characters.</i>
						<label for="characters">Characters: </label>
						<span id="characters">0</span><br>
					</p>
					<p>
						<label>Image</label>
						<input type="file" name="image" class="form-control" />
					</p>
					<p>
						<label>Active</label><br />
						<select name="active" class="form-select" required>
							<option value="Yes" selected>Yes</option>
							<option value="No">No</option>
                        </select>
					</p>
					<p>
						<label>Featured</label><br />
						<select name="featured" class="form-select" required>
							<option value="Yes">Yes</option>
							<option value="No" selected>No</option>
                        </select>
					</p>
					<p>
						<label>Category</label><br />
						<select name="category_id" class="form-select" required>
<?php
$crun = mysqli_query($connect, "SELECT * FROM `categories`");
while ($rw = mysqli_fetch_assoc($crun)) {
    echo '
                            <option value="' . $rw['id'] . '">' . $rw['category'] . '</option>
									';
}
?>
</select>
					</p>
                    
                    <p>
						<label>Tags</label>
						<input class="form-control" name="tags" value="" type="text" placeholder="php, blog, cms, ...">
						<i>Séparez les tags par une virgule ( , ).</i>
					</p>
                    <p>
						<label>Date de Publication (Optionnel)</label>
						<input class="form-control" name="publish_datetime" type="datetime-local">
						<i>Laissez vide pour publier immédiatement.</i>
					</p>                    
                    <p>
						<label>Content</label>
						<textarea class="form-control" id="summernote" rows="8" name="content" required></textarea>
					</p>
								
					<input type="submit" name="add" class="btn btn-primary col-12" value="Add" />
				</form>                      
            </div>
    </div>

<script>
$(document).ready(function() {
	$('#summernote').summernote({height: 350});
	
	var noteBar = $('.note-toolbar');
		noteBar.find('[data-toggle]').each(function() {
		$(this).attr('data-bs-toggle', $(this).attr('data-toggle')).removeAttr('data-toggle');
	});
});
</script>
<?php
include "footer.php";
?>