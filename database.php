<?php

function getDatabaseConnection() {

    $host = getenv("DB_HOST") ?: (defined("DB_HOST") ? DB_HOST : "projet-cloud.mysql.database.azure.com");
    $dbname = getenv("DB_NAME") ?: (defined("DB_NAME") ? DB_NAME : "projet_cloud_db");
    $username = getenv("DB_USER") ?: (defined("DB_USER") ? DB_USER : "adminazure");
    $password = getenv("DB_PASS") ?: (defined("DB_PASS") ? DB_PASS : "");

    
    if (substr($username, -1) === "@") {
        $username = rtrim($username, "@");
    }

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

        return $pdo;

    } catch (PDOException $e) {
        die("Erreur DB : " . $e->getMessage());
    }
}
