<?php
// En local (modifie si besoin)
define("DB_HOST", getenv("DB_HOST") ?: "projet-cloud.mysql.database.azure.com");
define("DB_USER", getenv("DB_USER") ?: "adminazure@projet-cloud");
define("DB_PASS", getenv("DB_PASS") ?: "Azerty123!");
define("DB_NAME", getenv("DB_NAME") ?: "projet_cloud_db");
?>
