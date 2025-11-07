<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
if(!isset($_SESSION)) {
    session_start();
}

// --- LOGIQUE MULTI-LANGUE ---
// 1. Détecter si l'utilisateur change de langue
if (isset($_GET['lang'])) {
    // S'assurer que seules 'fr' ou 'en' sont autorisées
    if ($_GET['lang'] == 'fr' || $_GET['lang'] == 'en') {
        $_SESSION['lang'] = $_GET['lang'];
    }
}

// 2. Définir la langue à charger
$default_lang = 'en'; // Anglais par défaut
$lang_to_load = isset($_SESSION['lang']) ? $_SESSION['lang'] : $default_lang;
$lang_file = 'lang/' . $lang_to_load . '.php';

// 3. Charger le fichier de langue (avec une sécurité)
if (file_exists($lang_file)) {
    include $lang_file;
} else {
    // Tenter de charger l'anglais si la langue sélectionnée n'existe pas
    include 'lang/' . $default_lang . '.php'; 
}
// --- FIN DE LA LOGIQUE MULTI-LANGUE ---


// SETTINGS
// Config file directory - Directory, where config file must be
define("CONFIG_FILE_DIRECTORY", "../");

// Config file name - Output file with config parameters (database, username etc.)
define("CONFIG_FILE_NAME", "config.php");

// According to directory hierarchy (you may add/remove "../" before CONFIG_FILE_DIRECTORY)
define("CONFIG_FILE_PATH", CONFIG_FILE_DIRECTORY . CONFIG_FILE_NAME);

// Config file name - config template file name
define("CONFIG_FILE_TEMPLATE", "config.tpl");

if (file_exists(CONFIG_FILE_PATH)) {
    echo '<meta http-equiv="refresh" content="0; url=../" />';
    exit;
}

function head()
{
    global $lang, $lang_to_load; // Rendre $lang et $lang_to_load disponibles

    $current_page = substr($_SERVER["SCRIPT_NAME"], strrpos($_SERVER["SCRIPT_NAME"], "/") + 1);  
    $page = 1; // Default
    if ($current_page == 'settings.php') {
        $page = 2; 
    } elseif ($current_page == 'done.php') {
        $page = 3;
    }

    // Définit les étapes en utilisant le tableau $lang
    $steps = [
        1 => ['icon' => 'fas fa-database', 'title' => $lang['step_db']],
        2 => ['icon' => 'fas fa-user-shield', 'title' => $lang['step_admin']],
        3 => ['icon' => 'fas fa-check-circle', 'title' => $lang['step_done']]
    ];
?>
<!DOCTYPE html>
<html lang="<?php echo $lang_to_load; ?>"> <head>
    <title><?php echo $lang['wizard_title']; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/img/favicon.png" type="image/png" />
    <meta charset="utf-8">
    <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">

    <link rel="stylesheet" href="css/style.css">

</head>

<body class="d-flex align-items-center py-4">

    <main class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-xl-7 col-lg-8 col-md-10">
                
                <div class="card shadow-lg installer-card">
                    
                    <div class="card-header text-center py-4 installer-header">
                        <h3 class="mb-0"><i class="fas fa-cogs text-primary"></i> phpBlog Installation</h3>
                        <p class="text-muted mb-0"><?php echo $lang['welcome_title']; ?></p>
                    </div>

                    <div class="card-body p-4 p-md-5">

						<div class="text-center mb-3">
                            <small>
                                <span class="text-muted mx-1">|</span><a href="?lang=fr" class="text-decoration-none <?php echo ($lang_to_load == 'fr' ? 'fw-bold' : 'text-muted'); ?>">
                                    <img src="img/fr.png" alt="Français" class="lang-flag"> 
                                    <?php echo $lang['lang_fr']; ?>
                                </a>
                                 <span class="text-muted mx-1">|</span>
                                <a href="?lang=en" class="text-decoration-none <?php echo ($lang_to_load == 'en' ? 'fw-bold' : 'text-muted'); ?>">
                                    <img src="img/en.png" alt="English" class="lang-flag"> 
                                    <?php echo $lang['lang_en']; ?>
                                </a><span class="text-muted mx-1">|</span>
                            </small>
                        </div>
                        
                        <ul class="nav nav-tabs nav-fill mb-4">
                            <?php foreach ($steps as $step_num => $step_data): ?>
                                <?php
                                    $is_active = ($page == $step_num);
                                    // Désactive les étapes futures
                                    $is_disabled = ($page < $step_num); 
                                ?>
                                <li class="nav-item">
                                    <a class="nav-link <?php if($is_active) echo 'active'; ?> <?php if($is_disabled) echo 'disabled'; ?>">
                                        <i class="<?php echo $step_data['icon']; ?> step-icon"></i>
                                        <?php echo $step_data['title']; ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        
                        <div class="tab-content" id="TabContent">
<?php
}

function footer()
{
    global $lang; // Rendre $lang disponible
?>
                        </div>
                        </div>
                    <div class="card-footer text-center bg-light border-0 py-3">
                        <small class="text-muted"><?php echo $lang['footer_text']; ?></small>
                    </div>
                    
                </div>
                </div>
        </div>
    </main>

</body>
</html>
<?php
}
?>