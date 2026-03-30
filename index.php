<?php
require_once "config.php";
require_once "database.php";

$db = getDatabaseConnection();

$page = $_GET['page'] ?? 'home';
$pageFile = "pages";