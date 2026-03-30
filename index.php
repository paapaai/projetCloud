<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once "config.php";
require_once "database.php";

$db = getDatabaseConnection();

$page = $_GET['page'] ?? 'home';
$pageFile = "pages";
?>
