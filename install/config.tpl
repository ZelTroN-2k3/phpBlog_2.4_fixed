<?php
$host     = "<DB_HOST>"; // Database Host
$user     = "<DB_USER>"; // Database Username
$password = "<DB_PASSWORD>"; // Database's user Password
$database = "<DB_NAME>"; // Database Name

$connect = new mysqli($host, $user, $password, $database);
// Checking Connection
if (mysqli_connect_errno()) {
    printf("Database connection failed: %s\n", mysqli_connect_error());
    exit();
}

mysqli_set_charset($connect, "utf8mb4");

// Charger les paramètres depuis la base de données
$settings_query = mysqli_query($connect, "SELECT * FROM `settings` WHERE id=1 LIMIT 1");
if (mysqli_num_rows($settings_query) > 0) {
    $settings = mysqli_fetch_assoc($settings_query);
} else {
    // Ne pas "die" ici car la table n'existe peut-être pas encore pendant l'installation
    // Cette partie est pour le site une fois installé.
    if (file_exists('install/index.php')) {
         echo '<meta http-equiv="refresh" content="0; url=install/index.php" />';
    } else {
         die("Erreur : Impossible de charger la configuration du site depuis la base de données.");
    }
}
?>