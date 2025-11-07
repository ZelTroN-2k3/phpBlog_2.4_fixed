<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - <?php echo SITE_DESCRIPTION; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <h1><a href="index.php"><?php echo SITE_NAME; ?></a></h1>
                <p class="tagline"><?php echo SITE_DESCRIPTION; ?></p>
            </div>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <?php if (isset($categories) && $categories): 
                        while ($cat = $categories->fetch()): ?>
                        <li><a href="category.php?id=<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></a></li>
                    <?php endwhile; endif; ?>
                    <li><a href="admin/login.php">Admin</a></li>
                </ul>
            </nav>
        </div>
    </header>
