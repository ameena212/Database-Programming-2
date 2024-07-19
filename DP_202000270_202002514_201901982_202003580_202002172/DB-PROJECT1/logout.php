<?php
// Destroying session and redirecting to index page
session_start();
session_destroy();
header("Location: index.php");
exit();
?>
