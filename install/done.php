<?php
include "core.php";
head();

$database_host     = $_SESSION['database_host'];
$database_username = $_SESSION['database_username'];
$database_password = $_SESSION['database_password'];
$database_name     = $_SESSION['database_name'];
$username          = $_SESSION['username'];
$email             = $_SESSION['email'];
$password          = password_hash($_SESSION['password'], PASSWORD_DEFAULT);

if (isset($_SERVER['HTTPS'])) {
    $htp = 'https';
} else {
    $htp = 'http';
}

@$db = new mysqli($database_host, $database_username, $database_password, $database_name);

// --- Calculer l'URL du site et sécuriser l'email ---
if (isset($_SERVER['HTTPS'])) {
    $htp = 'https';
} else {
    $htp = 'http';
}
$fullpath = "$htp://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$site_url = substr($fullpath, 0, strpos($fullpath, '/install'));

$site_url_safe = mysqli_real_escape_string($db, $site_url);
$email_safe_settings = mysqli_real_escape_string($db, $email);
// --- Fin du calcul ---

if ($db) {
    
    //Importing SQL Tables
    $query = '';
    
    $sql_dump_file = 'sql/database.sql';
    if (file_exists($sql_dump_file)) {
        $sql_dump = file($sql_dump_file);
        
        foreach ($sql_dump as $line) {
            
            $startWith = substr(trim($line), 0, 2);
            $endWith   = substr(trim($line), -1, 1);
            
            if (empty($line) || $startWith == '--' || $startWith == '/*' || $startWith == '//') {
                continue;
            }
            
            $query = $query . $line;
            if ($endWith == ';') {
                mysqli_query($db, $query) or die('Problem in executing the SQL query <b>' . $query . '</b>');
                $query = '';
            }
        }
    }
    // Config file creating and writing information
    $config_file = file_get_contents(CONFIG_FILE_TEMPLATE);
    $config_file = str_replace("<DB_HOST>", $database_host, $config_file);
    $config_file = str_replace("<DB_NAME>", $database_name, $config_file);
    $config_file = str_replace("<DB_USER>", $database_username, $config_file);
    $config_file = str_replace("<DB_PASSWORD>", $database_password, $config_file);
    
    $link  = new mysqli($database_host, $database_username, $database_password, $database_name);
    
    // --- Mettre à jour site_url et email dans la nouvelle table settings ---
    mysqli_query($link, "UPDATE `settings` SET site_url='$site_url_safe', email='$email_safe_settings' WHERE id=1");
    // --- Fin de la mise à jour ---

    // --- MODIFICATION DE SÉCURITÉ ---
    // Échapper les variables avant de les insérer dans la requête SQL
    $username_safe = mysqli_real_escape_string($link, $username);
    $email_safe    = mysqli_real_escape_string($link, $email);
    
    // Utiliser les variables sécurisées dans la requête
    $query = mysqli_query($link, "INSERT INTO `users` (username, password, email, role) VALUES ('$username_safe', '$password', '$email_safe', 'Admin')");
    // --- FIN DE LA MODIFICATION ---
	
    @chmod(CONFIG_FILE_PATH, 0777);
    @$f = fopen(CONFIG_FILE_PATH, "w+");
    if (!fwrite($f, $config_file) > 0) {
        echo $lang['config_write_error'];
    }
    fclose($f);
    
} else {
    echo $lang['db_connect_error'] . '<br />';
}
?>
<center>
	<div class="alert alert-success">
		<i class="fas fa-home"></i> <?php echo $lang['done_success']; ?>
	</div>
    <div class="alert alert-info">
		<h6 class="alert-heading"><i class="fab fa-google"></i> <?php echo $lang['recaptcha_title']; ?></h6>
		<p><?php echo $lang['recaptcha_desc']; ?></p>
		<hr>
		<p class="mb-1">
			<strong><?php echo $lang['recaptcha_key']; ?></strong><br>
			<small><code style="word-wrap: break-word;">6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI</code></small>
		</p>
		<p class="mb-0">
			<strong><?php echo $lang['recaptcha_secret']; ?></strong><br>
			<small><code style="word-wrap: break-word;">6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe</code></small>
		</p>
	</div>   
	<div class="alert alert-danger">
		<i class="fas fa-exclamation-triangle"></i> <?php echo $lang['done_security_warning']; ?>
	</div>
		<a href="../" class="btn-success btn col-12"><i class="fas fa-arrow-circle-right"></i> <?php echo $lang['done_continue']; ?></a>
</center>
<?php
footer();
?>