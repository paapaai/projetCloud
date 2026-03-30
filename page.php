
<?php
global $db;

$feedback = "";

try {
	// First real DB processing step: persist simple messages.
	$db->exec(
		"CREATE TABLE IF NOT EXISTS messages (\n"
		. "id INT AUTO_INCREMENT PRIMARY KEY,\n"
		. "content VARCHAR(255) NOT NULL,\n"
		. "created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP\n"
		. ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
	);

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$action = $_POST['action'] ?? 'add';

		if ($action === 'clear') {
			$db->exec("DELETE FROM messages");
			$feedback = "Tous les messages ont ete supprimes.";
		} else {
			$content = trim($_POST['content'] ?? '');

			if ($content === '') {
				$feedback = "Le message est vide.";
			} else {
				$insert = $db->prepare("INSERT INTO messages (content) VALUES (:content)");
				$insert->execute(['content' => $content]);
				$feedback = "Message enregistre.";
			}
		}
	}

	$stmt = $db->query("SELECT id, content, created_at FROM messages ORDER BY id DESC LIMIT 20");
	$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
	$feedback = "Erreur DB: " . $e->getMessage();
	$messages = [];
}
?>

<h1>Bienvenue sur mon back PHP</h1>
<p>Connexion Azure OK. Tu peux maintenant enregistrer des donnees :</p>

<?php if ($feedback !== ""): ?>
<p><strong><?= htmlspecialchars($feedback, ENT_QUOTES, 'UTF-8') ?></strong></p>
<?php endif; ?>

<form method="post">
	<input type="hidden" name="action" value="add">
	<label for="content">Nouveau message :</label>
	<input type="text" id="content" name="content" maxlength="255" required>
	<button type="submit">Ajouter</button>
</form>

<form method="post" onsubmit="return confirm('Supprimer tous les messages ?');">
	<input type="hidden" name="action" value="clear">
	<button type="submit">Vider la base</button>
</form>

<h2>Derniers messages</h2>
<?php if (count($messages) === 0): ?>
<p>Aucun message en base pour le moment.</p>
<?php else: ?>
<ul>
	<?php foreach ($messages as $message): ?>
	<li>
		#<?= (int)$message['id'] ?> -
		<?= htmlspecialchars($message['content'], ENT_QUOTES, 'UTF-8') ?>
		(<?= htmlspecialchars($message['created_at'], ENT_QUOTES, 'UTF-8') ?>)
	</li>
	<?php endforeach; ?>
</ul>
<?php endif; ?>
