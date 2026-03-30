<?php
$host = getenv("DB_HOST") ?: "projet-cloud.mysql.database.azure.com";
$dbname = getenv("DB_NAME") ?: "projet_cloud_db";
$username = getenv("DB_USER") ?: "adminazure";
$password = getenv("DB_PASS") ?: "Azerty123!";

try {
    $pdo = new PDO(
        "mysql:host=$host;port=3306;dbname=$dbname;charset=utf8",
        $username,
        $password,
        [
            PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-certificates.crt',
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );

} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
