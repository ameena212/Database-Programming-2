<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($lang); ?>">
    <head>
        <!-- Meta tags -->
        <meta charset="UTF-8">
        <title>Online Event Management System</title>

        <!-- Stylesheet -->
        <link rel="stylesheet" href="css/styles.css">

        <!-- JavaScript -->
        <script src="js/script.js" defer></script>
        <?php if ($lang == 'ar'): ?>
            <!-- Right-to-left style for Arabic -->
            <style>
                body {
                    direction: rtl;
                    text-align: right;
                }
            </style>
        <?php endif; ?>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    </head>
    <body>
        <header>
            <img src="images/logo.png" alt="logo" style="width: 300px">
            <nav>
                <!-- Language navigation -->
                <a href="?lang=en" style="color: white;">English</a> | 
                <a href="?lang=fr" style="color: white;">Français</a> | 
                <a href="?lang=ar" style="color: white;">العربية</a>
            </nav>
        </header>
        <?php include 'navbar.php'; ?>
