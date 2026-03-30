<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "INDEX OK<br>";

require_once "config.php";
echo "CONFIG OK<br>";

require_once "database.php";
echo "DB FILE OK<br>";

$db = getDatabaseConnection();
echo "DB CONNECTED<br>";

$page = $_GET['page'] ?? 'home';
echo "PAGE = " . $page . "<br>";

$file = __DIR__ . "/pages/" . $page . ".php";
echo "FILE = " . $file . "<br>";

if (file_exists($file)) {
    echo "FILE EXISTS<br>";
    require $file;
} else {
    echo "Page introuvable";
}
