<?php
function loadLanguage($lang = 'en') {
    $langFile = __DIR__ . '/../lang/' . $lang . '.php';
    if (file_exists($langFile)) {
        return include($langFile);
    }
    return include(__DIR__ . '/../lang/en.php'); // default to English if the file doesn't exist
}

$lang = 'en'; // default language
if (isset($_GET['lang']) && in_array($_GET['lang'], ['en', 'fr', 'ar'])) {
    $lang = $_GET['lang'];
}

$translations = loadLanguage($lang);