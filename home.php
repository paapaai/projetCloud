<?php
$stmt = $db->query("SELECT 'Hello from Azure!' as message");
$row = $stmt->fetch();
?>

<h1>Bienvenue sur mon back PHP</h1>
<p>Message de la base de données : <strong><?= $row['message'] ?></strong></p>