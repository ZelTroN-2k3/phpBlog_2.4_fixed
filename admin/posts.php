<?php
include "header.php";

// [ BLOC DE SUPPRESSION SÉCURISÉ ]
if (isset($_GET['delete-id'])) {
    $id     = (int) $_GET["delete-id"];
    
    // On vérifie qui est l'auteur
    $check_sql = mysqli_query($connect, "SELECT author_id FROM `posts` WHERE id='$id'");
    $check_row = mysqli_fetch_assoc($check_sql);
    
    // On autorise la suppression SEULEMENT si l'utilisateur est Admin OU s'il est l'auteur
    if ($user['role'] == 'Admin' || ($user['role'] == 'Editor' && $check_row['author_id'] == $user['id'])) {
        $query  = mysqli_query($connect, "DELETE FROM `posts` WHERE id='$id'");
        $query2 = mysqli_query($connect, "DELETE FROM `comments` WHERE post_id='$id'");
        $query3 = mysqli_query($connect, "DELETE FROM `post_tags` WHERE post_id='$id'");
    }
}
?>
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h3 class="h3"><i class="fas fa-list"></i> Posts</h3>
	</div>
	  
<?php
// [ BLOC D'ÉDITION SÉCURISÉ ]
if (isset($_GET['edit-id'])) {
    $id  = (int) $_GET["edit-id"];
    $sql = mysqli_query($connect, "SELECT * FROM `posts` WHERE id = '$id'");
    $row = mysqli_fetch_assoc($sql);
    
    if (empty($id) || mysqli_num_rows($sql) == 0) {
        echo '<meta http-equiv="refresh" content="0; url=posts.php">';
		exit; 
    }

    // Sécurité Rôle Editor
    if ($user['role'] == 'Editor' && $row['author_id'] != $user['id']) {
        echo '<meta http-equiv="refresh" content="0; url=posts.php">';
		exit;
    }
    
	if (isset($_POST['submit'])) {
        $title       = addslashes($_POST['title']);
		$slug        = generateSeoURL($title);
        $image       = $row['image']; 
        $active      = addslashes($_POST['active']);
		$featured    = addslashes($_POST['featured']);
        $category_id = addslashes($_POST['category_id']);
        $content     = addslashes($_POST['content']); // Correction de htmlspecialchars
        $tags_input  = addslashes($_POST['tags']); 
        
        // --- Logique de Planification ---
        $publish_datetime_input = $_POST['publish_datetime'];
        
        if (!empty($publish_datetime_input)) {
            $publish_timestamp    = strtotime($publish_datetime_input);
            $publish_datetime_sql = "'" . date('Y-m-d H:i:s', $publish_timestamp) . "'"; 
            $date                 = date($settings['date_format'], $publish_timestamp);
            $time                 = date('H:i', $publish_timestamp);
        } else {
            $publish_datetime_sql = "NOW()";
            $current_timestamp    = time();
            $date                 = date($settings['date_format'], $current_timestamp);
            $time                 = date('H:i', $current_timestamp);
        }
        
        // --- Logique d'image ---
        if (@$_FILES['image']['name'] != '') {
            $target_dir    = "uploads/posts/";
            $target_file   = $target_dir . basename($_FILES["image"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            
            $uploadOk = 1;
            $check    = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check === false) { $uploadOk = 0; }
            if ($_FILES["image"]["size"] > 10000000) { $uploadOk = 0; }
            
            if ($uploadOk == 1) {
                $string     = "0123456789wsderfgtyhjuk";
                $new_string = str_shuffle($string);
                $location   = "../uploads/posts/image_$new_string.$imageFileType";
                move_uploaded_file($_FILES["image"]["tmp_name"], $location);
                $image = 'uploads/posts/image_' . $new_string . '.' . $imageFileType . '';
            }
        }
        
        // --- Requête UPDATE ---
        $update = mysqli_query($connect, "UPDATE `posts` SET 
            title = '$title', 
            slug = '$slug', 
            image = '$image', 
            active = '$active', 
            featured = '$featured', 
            category_id = '$category_id', 
            content = '$content',
            publish_datetime = $publish_datetime_sql, 
            date = '$date', 
            time = '$time' 
            WHERE id = '$id'");

        // --- Logique de mise à jour des Tags ---
        mysqli_query($connect, "DELETE FROM `post_tags` WHERE post_id = '$id'");
        
        if (!empty($tags_input)) {
            $tags_array = explode(',', $tags_input);
            foreach ($tags_array as $tag_name) {
                $tag_name = trim($tag_name);
                if (!empty($tag_name)) {
                    $tag_slug = generateSeoURL($tag_name);
                    
                    $check_tag_sql = "SELECT tag_id FROM `tags` WHERE tag_slug = '$tag_slug' LIMIT 1";
                    $check_tag_query = mysqli_query($connect, $check_tag_sql);
                    
                    if (mysqli_num_rows($check_tag_query) > 0) {
                        $existing_tag = mysqli_fetch_assoc($check_tag_query);
                        $tag_id = $existing_tag['tag_id'];
                    } else {
                        $insert_tag_sql = "INSERT INTO `tags` (tag_name, tag_slug) VALUES ('$tag_name', '$tag_slug')";
                        mysqli_query($connect, $insert_tag_sql);
                        $tag_id = mysqli_insert_id($connect);
                    }
                    
                    if ($id > 0 && $tag_id > 0) {
                        $insert_post_tag_sql = "INSERT INTO `post_tags` (post_id, tag_id) VALUES ('$id', '$tag_id')";
                        mysqli_query($connect, $insert_post_tag_sql);
                    }
                }
            }
        }
        
        echo '<meta http-equiv="refresh" content="0;url=posts.php">';
		exit;
    }
?>
    <div class="card">
        <h6 class="card-header">Edit Post</h6>         
        <div class="card-body">
            <form name="post_form" action="" method="post" enctype="multipart/form-data">
                <p>
                    <label>Title</label>
                    <input class="form-control" name="title" id="title" value="<?php echo htmlspecialchars($row['title']); ?>" type="text" oninput="countText()" required>
                    <i>For best SEO keep title under 50 characters.</i>
                    <label for="characters">Characters: </label>
                    <span id="characters">0</span><br>
                </p>
                <p>
                    <label>Image</label>
                    <input type="file" name="image" class="form-control" />
					<?php if ($row['image'] != ''): ?>
					<br /><img src="../<?php echo htmlspecialchars($row['image']); ?>" width="100" />
					<?php endif; ?>
                </p>
                <p>
                    <label>Active</label><br />
                    <select name="active" class="form-select" required>
                        <option value="Yes" <?php if ($row['active'] == 'Yes') { echo 'selected'; } ?>>Yes</option>
                        <option value="No" <?php if ($row['active'] == 'No') { echo 'selected'; } ?>>No</option>
                    </select>
                </p>
                <p>
                    <label>Featured</label><br />
                    <select name="featured" class="form-select" required>
                        <option value="Yes" <?php if ($row['featured'] == 'Yes') { echo 'selected'; } ?>>Yes</option>
                        <option value="No" <?php if ($row['featured'] == 'No') { echo 'selected'; } ?>>No</option>
                    </select>
                </p>
                <p>
                    <label>Category</label><br />
                    <select name="category_id" class="form-select" required>
                    <?php
						$crun = mysqli_query($connect, "SELECT * FROM `categories`");
						while ($rw = mysqli_fetch_assoc($crun)) {
							$selected = ($row['category_id'] == $rw['id']) ? 'selected' : '';
							echo '<option value="' . $rw['id'] . '" ' . $selected . '>' . $rw['category'] . '</option>';
						}
					?>
                    </select>
                </p>
                
                <p>
                    <label>Tags</label>
                    <?php
                        $tags_sql = "SELECT t.tag_name FROM `tags` t INNER JOIN `post_tags` pt ON t.tag_id = pt.tag_id WHERE pt.post_id = '$id'";
                        $tags_query = mysqli_query($connect, $tags_sql);
                        $tags_list = [];
                        while($tag_row = mysqli_fetch_assoc($tags_query)) {
                            $tags_list[] = htmlspecialchars($tag_row['tag_name']);
                        }
                        $tags_value = implode(', ', $tags_list); 
                    ?>
                    <input class="form-control" name="tags" value="<?php echo $tags_value; ?>" type="text" placeholder="php, blog, cms, ...">
                    <i>Séparez les tags par une virgule ( , ).</i>
                </p>
                
                <p>
                    <label>Date de Publication (Optionnel)</label>
                    <?php
                        $publish_value = '';
                        if (!empty($row['publish_datetime'])) {
                            if (strtotime($row['publish_datetime']) > 0) {
                                $publish_value = date('Y-m-d\TH:i', strtotime($row['publish_datetime']));
                            }
                        }
                    ?>
                    <input class="form-control" name="publish_datetime" type="datetime-local" value="<?php echo $publish_value; ?>">
                    <i>Laissez vide pour publier immédiatement.</i>
                </p>
                
                <p>
                    <label>Content</label>
                    <textarea class="form-control" id="summernote" rows="8" name="content" required><?php echo htmlspecialchars($row['content']); ?></textarea>
                </p>
                            
                <input type="submit" name="submit" class="btn btn-primary col-12" value="Update" />
            </form>                      
        </div>
    </div>
<?php
} else {
// [ BLOC D'AFFICHAGE LISTE ]
?>
	<div class="card">
		<h6 class="card-header">Posts</h6>
		<div class="card-body">
		
		<table id="dt-basic" class="table table-striped table-bordered">
				<thead>
					<tr>
						<th>Image</th>
						<th>Title</th>
						<th>Author</th>
						<th>Role</th> <th>Date</th>
						<th>Active</th>
						<th>Category</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
<?php
// DÉBUT DE LA MODIFICATION - Requête SQL avec JOIN
$sql_query_string = "
    SELECT p.*, u.username AS author_username, u.role AS author_role 
    FROM `posts` p
    LEFT JOIN `users` u ON p.author_id = u.id
";

if ($user['role'] == 'Editor') {
    $sql_query_string .= " WHERE p.author_id = '{$user['id']}'";
}

$sql_query_string .= " ORDER BY p.id DESC";

$sql = mysqli_query($connect, $sql_query_string);
// FIN DE LA MODIFICATION

while ($row = mysqli_fetch_assoc($sql)) {
	$csql = mysqli_query($connect, "SELECT * FROM `categories` WHERE id = '" . $row['category_id'] . "'");
    $cat  = mysqli_fetch_assoc($csql);
    
    $featured = '';
    if ($row['featured'] == 'Yes') {
        $featured = '<span class="badge bg-primary">Featured</span>';
    }
    
    // MODIFICATION : Logique pour l'auteur et le rôle
    $author_username = $row['author_username'] ? htmlspecialchars($row['author_username']) : '-';
    $author_role = $row['author_role'] ? htmlspecialchars($row['author_role']) : 'Unknown';
    
    $badge = '';
    if ($author_role == 'Admin') {
        $badge = '<h6><span class="badge bg-danger">Admin</span></h6>';
    } else if ($author_role == 'Editor') {
        $badge = '<h6><span class="badge bg-success">Editor</span></h6>';
    } else if ($author_role == 'User') {
        $badge = '<h6><span class="badge bg-primary">User</span></h6>';
    } else {
        $badge = '<h6><span class="badge bg-secondary">' . $author_role . '</span></h6>';
    }
    // FIN MODIFICATION
    
    echo '
					<tr>
						<td>';
    if ($row['image'] != '') {
        echo '
                    <center><img src="../' . $row['image'] . '" width="45px" height="45px" /></center>
					';
    }
    echo '</td>
						<td>' . $row['title'] . ' ' . $featured . '</td>
						<td>' . $author_username . '</td> <td>' . $badge . '</td> <td data-sort="' . strtotime($row['date']) . '">' . date($settings['date_format'], strtotime($row['date'])) . ', ' . $row['time'] . '</td>
						<td>';
	if($row['active'] == "Yes") {
		echo '<span class="badge bg-success">Yes</span>';
	} else {
		echo '<span class="badge bg-danger">No</span>';
	}
	echo '</td>
						<td>' . $cat['category'] . '</td>
						<td>
							<a href="?edit-id=' . $row['id'] . '" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</a>
							<a href="?delete-id=' . $row['id'] . '" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</a>
						</td>
					</tr>
	';
}
?>
				</tbody>
			</table>
		</div>
	</div>
<?php
}
?>

<script>
$(document).ready(function() {
	
	$('#dt-basic').dataTable( {
		"responsive": true,
		"order": [[ 4, "desc" ]], // MODIFICATION : L'index de la colonne Date est maintenant 4
		"columnDefs": [
			{ "orderable": false, "targets": [0, 7] } // MODIFICATION : L'index des Actions est maintenant 7
		]
	} );
	
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