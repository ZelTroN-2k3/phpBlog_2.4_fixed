<?php
include "header.php";

if (isset($_GET['delete_bgrimg'])) {
    // Supprimer l'ancien fichier s'il existe
    if ($settings['background_image'] != '' && file_exists('../' . $settings['background_image'])) {
        unlink('../' . $settings['background_image']);
    }

    // Mettre à jour la base de données
    mysqli_query($connect, "UPDATE `settings` SET background_image='' WHERE id=1");

    echo '<meta http-equiv="refresh" content="0;url=settings.php">';
}

if (isset($_POST['save'])) {

    $image = $settings['background_image']; // Garder l'ancienne image par défaut

    if (@$_FILES['background_image']['name'] != '') {
        $target_dir    = "uploads/other/";
        $target_file   = $target_dir . basename($_FILES["background_image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $uploadOk = 1;

        // ... (Le reste de votre logique de vérification de l'upload reste identique) ...
        $check = getimagesize($_FILES["background_image"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo '<div class="alert alert-danger">The file is not an image.</div>';
            $uploadOk = 0;
        }

        if ($_FILES["background_image"]["size"] > 2000000) {
            echo '<div class="alert alert-warning">Sorry, the image file size is too large. Limit: 2 MB.</div>';
            $uploadOk = 0;
        }

        if ($uploadOk == 1) {
            // Supprimer l'ancienne image si elle existe et est différente
            if ($settings['background_image'] != '' && file_exists('../' . $settings['background_image'])) {
                unlink('../' . $settings['background_image']);
            }

            $string     = "0123456789wsderfgtyhjuk";
            $new_string = str_shuffle($string);
            $location   = "../uploads/other/bgr_$new_string.$imageFileType";
            move_uploaded_file($_FILES["background_image"]["tmp_name"], $location);
            $image = 'uploads/other/bgr_' . $new_string . '.' . $imageFileType . '';
        }
    } // Fin de la gestion de l'upload

    // Préparer les données pour la BDD
    $sitename = mysqli_real_escape_string($connect, $_POST['sitename']);
    $description = mysqli_real_escape_string($connect, $_POST['description']);
    $email = mysqli_real_escape_string($connect, $_POST['email']);
    $gcaptcha_sitekey = mysqli_real_escape_string($connect, $_POST['gcaptcha-sitekey']);
    $gcaptcha_secretkey = mysqli_real_escape_string($connect, $_POST['gcaptcha-secretkey']);
    $head_customcode = mysqli_real_escape_string($connect, base64_encode($_POST['head-customcode']));
    $facebook = mysqli_real_escape_string($connect, $_POST['facebook']);
    $instagram = mysqli_real_escape_string($connect, $_POST['instagram']);
    $twitter = mysqli_real_escape_string($connect, $_POST['twitter']);
    $youtube = mysqli_real_escape_string($connect, $_POST['youtube']);
    $linkedin = mysqli_real_escape_string($connect, $_POST['linkedin']);
    $comments = mysqli_real_escape_string($connect, $_POST['comments']);
    $rtl = mysqli_real_escape_string($connect, $_POST['rtl']);
    $date_format = mysqli_real_escape_string($connect, $_POST['date_format']);
    $layout = mysqli_real_escape_string($connect, $_POST['layout']);
    $latestposts_bar = mysqli_real_escape_string($connect, $_POST['latestposts_bar']);
    $sidebar_position = mysqli_real_escape_string($connect, $_POST['sidebar_position']);
    $posts_per_row = mysqli_real_escape_string($connect, $_POST['posts_per_row']);
    $theme = mysqli_real_escape_string($connect, $_POST['theme']);
    $background_image_db = mysqli_real_escape_string($connect, $image); // $image vient de la logique d'upload

    // Construire la requête UPDATE
    $sql = "UPDATE `settings` SET 
                sitename = '$sitename',
                description = '$description',
                email = '$email',
                gcaptcha_sitekey = '$gcaptcha_sitekey',
                gcaptcha_secretkey = '$gcaptcha_secretkey',
                head_customcode = '$head_customcode',
                facebook = '$facebook',
                instagram = '$instagram',
                twitter = '$twitter',
                youtube = '$youtube',
                linkedin = '$linkedin',
                comments = '$comments',
                rtl = '$rtl',
                date_format = '$date_format',
                layout = '$layout',
                latestposts_bar = '$latestposts_bar',
                sidebar_position = '$sidebar_position',
                posts_per_row = '$posts_per_row',
                theme = '$theme',
                background_image = '$background_image_db'
            WHERE id = 1";

    // Exécuter la mise à jour
    if(mysqli_query($connect, $sql)) {
        echo '<meta http-equiv="refresh" content="0;url=settings.php">';
    } else {
        echo '<div class="alert alert-danger">Erreur lors de la mise à jour des paramètres : ' . mysqli_error($connect) . '</div>';
    }
}
?>
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
			<h3 class="h3"><i class="fas fa-cogs"></i> Settings</h3>
		</div>

		<div class="card">
			<h6 class="card-header">Settings</h6>         
			<div class="card-body">
				<form action="" method="post" enctype="multipart/form-data">
					<p>
						<label>Site Name</label>
						<input class="form-control" name="sitename" value="<?php
echo $settings['sitename'];
?>" type="text" required>
					</p>
					<p>
						<label>Description</label>
						<textarea class="form-control" name="description" required><?php
echo $settings['description'];
?></textarea>
					</p>
					<p>
						<label>Website's E-Mail Address</label>
						<input class="form-control" name="email" type="email" value="<?php
echo $settings['email'];
?>" type="email" required>
					</p>
					<div class="row">
						<div class="col-md-6">
							<p>
								<label>reCAPTCHA v2 Site Key:</label>
								<input class="form-control" name="gcaptcha-sitekey" value="<?php
echo $settings['gcaptcha_sitekey'];
?>" type="text" required>
							</p>
						</div>
						<div class="col-md-6">
							<p>
								<label>reCAPTCHA v2 Secret Key:</label>
								<input class="form-control" name="gcaptcha-secretkey" value="<?php
echo $settings['gcaptcha_secretkey'];
?>" type="text" required>
							</p>
						</div>
					</div>
					<p>
						<label>Custom code for < head > tag</label>
						<textarea name="head-customcode" class="form-control" rows="8" placeholder="For example: Google Analytics tracking code can be placed here"><?php
echo base64_decode($settings['head_customcode']);
?></textarea>
					</p>
					<p>
						<label>Facebook Profile</label>
						<input class="form-control" name="facebook" type="url" value="<?php
echo $settings['facebook'];
?>" type="text">
					</p>
					<p>
						<label>Instagram Profile</label>
						<input class="form-control" name="instagram" type="url" value="<?php
echo $settings['instagram'];
?>" type="text">
					</p>
					<p>
						<label>Twitter Profile</label>
						<input class="form-control" name="twitter" type="url" value="<?php
echo $settings['twitter'];
?>" type="text">
					</p>
					<p>
						<label>Youtube Profile</label>
						<input class="form-control" name="youtube" type="url" value="<?php
echo $settings['youtube'];
?>" type="text">
					</p>
					<p>
						<label>LinkedIn Profile</label>
						<input class="form-control" name="linkedin" type="url" value="<?php
echo $settings['linkedin'];
?>" type="text">
					</p>
					<div class="row">
						<div class="col-md-6">
							<p>
								<label>RTL Content (Right-To-Left)</label>
								<select name="rtl" class="form-select" required>
									<option value="No" <?php
if ($settings['rtl'] == "No") {
	echo 'selected';
}
?>>No</option>
									<option value="Yes" <?php
if ($settings['rtl'] == "Yes") {
	echo 'selected';
}
?>>Yes</option>
								</select>
							</p>
						</div>
						<div class="col-md-6">
							<p>
								<label>Comments Section</label>
								<select name="comments" class="form-select" required>
									<option value="guests" <?php
if ($settings['comments'] == "guests") {
	echo 'selected';
}
		?>>Registration not required</option>
									<option value="registered" <?php
if ($settings['comments'] == "registered") {
	echo 'selected';
}
?>>Registration required</option>
								</select>
							</p>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<p>
								<label>Date Format</label><br />
								<select name="date_format" class="form-select" required>
									<option value="d.m.Y" <?php
if ($settings['date_format'] == "d.m.Y") {
	echo 'selected';
}
?>><?php echo date("d.m.Y"); ?></option>
									<option value="m.d.Y" <?php
if ($settings['date_format'] == "m.d.Y") {
	echo 'selected';
}
?>><?php echo date("m.d.Y"); ?></option>
									<option value="Y.m.d" <?php
if ($settings['date_format'] == "Y.m.d") {
	echo 'selected';
}
?>><?php echo date("Y.m.d"); ?></option>
									<option disabled>───────────</option>
									<option value="d F Y" <?php
if ($settings['date_format'] == "d F Y") {
	echo 'selected';
}
?>><?php echo date("d F Y"); ?></option>
									<option value="F j, Y" <?php
if ($settings['date_format'] == "F j, Y") {
	echo 'selected';
}
?>><?php echo date("F j, Y"); ?></option>
									<option value="Y F j" <?php
if ($settings['date_format'] == "Y F j") {
	echo 'selected';
}
?>><?php echo date("Y F j"); ?></option>
									<option disabled>───────────</option>
									<option value="d-m-Y" <?php
if ($settings['date_format'] == "d-m-Y") {
	echo 'selected';
}
?>><?php echo date("d-m-Y"); ?></option>
									<option value="m-d-Y" <?php
if ($settings['date_format'] == "m-d-Y") {
	echo 'selected';
}
?>><?php echo date("m-d-Y"); ?></option>
									<option value="Y-m-d" <?php
if ($settings['date_format'] == "Y-m-d") {
	echo 'selected';
}
?>><?php echo date("Y-m-d"); ?></option>
									<option disabled>───────────</option>
									<option value="d/m/Y" <?php
if ($settings['date_format'] == "d/m/Y") {
	echo 'selected';
}
?>><?php echo date("d/m/Y"); ?></option>
									<option value="m/d/Y" <?php
if ($settings['date_format'] == "m/d/Y") {
	echo 'selected';
}
?>><?php echo date("m/d/Y"); ?></option>
									<option value="Y/m/d" <?php
if ($settings['date_format'] == "Y/m/d") {
	echo 'selected';
}
?>><?php echo date("Y/m/d"); ?></option>
								</select>
							</p>
						</div>
						<div class="col-md-4">
							<p>
								<label>Layout</label>
								<select name="layout" class="form-select" required>
									<option value="Wide" <?php
if ($settings['layout'] == "Wide") {
	echo 'selected';
}
?>>Wide (Full-Sized)</option>
									<option value="Boxed" <?php
if ($settings['layout'] == "Boxed") {
	echo 'selected';
}
?>>Boxed</option>
								</select>
							</p>
						</div>
						<div class="col-md-4">
							<p>
								<label>Latest Posts bar</label>
								<select name="latestposts_bar" class="form-select" required>
									<option value="Enabled" <?php
if ($settings['latestposts_bar'] == "Enabled") {
	echo 'selected';
}
?>>Enabled</option>
									<option value="Disabled" <?php
if ($settings['latestposts_bar'] == "Disabled") {
	echo 'selected';
}
?>>Disabled</option>
								</select>
							</p>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<p>
								<label>Sidebar Position</label>
								<select name="sidebar_position" class="form-select" required>
									<option value="Left" <?php
if ($settings['sidebar_position'] == "Left") {
	echo 'selected';
}
?>>Left</option>
									<option value="Right" <?php
if ($settings['sidebar_position'] == "Right") {
	echo 'selected';
}
?>>Right</option>
								</select>
							</p>
						</div>
						<div class="col-md-4">
							<p><label>Homepage posts per row</label><br />
								<select name="posts_per_row" class="form-select" required>
									<option value="2" <?php
if ($settings['posts_per_row'] == "2") {
	echo 'selected';
}
?>>2</option>
									<option value="3" <?php
if ($settings['posts_per_row'] == "3") {
	echo 'selected';
}
?>>3</option>
								</select>
							</p>
						</div>
						<div class="col-md-4">
							<p>
								<label>Theme</label>
								<select class="form-select" name="theme" required>
<?php
$themes = array("Bootstrap 5", "Cerulean", "Cosmo", "Darkly", "Flatly", "Journal", "Litera", "Lumen", "Lux", "Materia", "Minty", "Morph", "Pulse", "Sandstone", "Simplex", "Sketchy", "Slate", "Solar", "Spacelab", "Superhero", "United", "Vapor", "Yeti", "Zephyr");
foreach ($themes as $design) {
	if ($settings['theme'] == $design) {
		$selected = 'selected';
	} else {
		$selected = '';
	}
	echo '<option value="'.$design.'" '.$selected.'>'.$design.'</option>';
}
?>
								</select>
							</p>
						</div>
					</div>
					<p>
						<label>Background Image</label>
<?php
if ($settings['background_image'] != "") {
	echo '
						<div class="row d-flex justify-content-center align-items-md-center">
							<img src="../' . $settings['background_image'] . '" class="col-md-2" width="128px" height="128px" />
							<a href="?delete_bgrimg" class="btn btn-sm btn-danger col-md-2">
								<i class="fas fa-trash"></i> Delete
							</a>
						</div>';
}
?>
						<input name="background_image" class="form-control" type="file" id="formFile">
					</p>
					<div class="form-actions">
						<input type="submit" name="save" class="btn btn-success col-12" value="Save" />
					</div>
				</form>                           
			</div>
		</div>   
<?php
include "footer.php";
?>