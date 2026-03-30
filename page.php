
<?php
global $db;

$feedback = "";
$feedbackType = "info";

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
			$feedbackType = "success";
		} else {
			$content = trim($_POST['content'] ?? '');

			if ($content === '') {
				$feedback = "Le message est vide.";
				$feedbackType = "warning";
			} else {
				$insert = $db->prepare("INSERT INTO messages (content) VALUES (:content)");
				$insert->execute(['content' => $content]);
				$feedback = "Message enregistre.";
				$feedbackType = "success";
			}
		}
	}

	$stmt = $db->query("SELECT id, content, created_at FROM messages ORDER BY id DESC LIMIT 20");
	$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
	$feedback = "Erreur DB: " . $e->getMessage();
	$feedbackType = "error";
	$messages = [];
}
?>

<style>
	@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&family=IBM+Plex+Mono:wght@400;600&display=swap');

	:root {
		--bg: #f5efe6;
		--bg-soft: #fffaf2;
		--ink: #172126;
		--muted: #4f5a61;
		--primary: #0f766e;
		--primary-hover: #0b5f59;
		--danger: #b42318;
		--danger-hover: #8e1a13;
		--warning: #b95f00;
		--card: #ffffff;
		--border: #d8d1c7;
	}

	body {
		margin: 0;
		font-family: 'Space Grotesk', sans-serif;
		background:
			radial-gradient(circle at 12% 18%, rgba(15, 118, 110, 0.14), transparent 45%),
			radial-gradient(circle at 90% 8%, rgba(185, 95, 0, 0.12), transparent 42%),
			var(--bg);
		color: var(--ink);
	}

	.app {
		max-width: 880px;
		margin: 40px auto;
		padding: 24px;
	}

	.hero {
		background: linear-gradient(135deg, #fbf8f2, #f2ece0);
		border: 1px solid var(--border);
		border-radius: 20px;
		padding: 22px;
		box-shadow: 0 14px 38px rgba(23, 33, 38, 0.09);
	}

	h1 {
		margin: 0;
		font-size: clamp(1.7rem, 3.2vw, 2.4rem);
		letter-spacing: -0.02em;
	}

	.subtitle {
		margin-top: 8px;
		color: var(--muted);
		font-size: 1rem;
	}

	.feedback {
		margin-top: 16px;
		padding: 12px 14px;
		border-radius: 12px;
		font-weight: 600;
		border: 1px solid transparent;
	}

	.feedback.success {
		background: #ebf8f7;
		border-color: #b6e3dd;
		color: #0b5f59;
	}

	.feedback.warning {
		background: #fff4e5;
		border-color: #f4c890;
		color: var(--warning);
	}

	.feedback.error {
		background: #fff0ef;
		border-color: #f4b3ae;
		color: var(--danger);
	}

	.panel {
		margin-top: 18px;
		background: var(--card);
		border: 1px solid var(--border);
		border-radius: 16px;
		padding: 18px;
	}

	.panel h2 {
		margin-top: 0;
		margin-bottom: 14px;
		font-size: 1.25rem;
	}

	.form-grid {
		display: grid;
		gap: 10px;
		grid-template-columns: 1fr auto;
		align-items: end;
	}

	label {
		display: block;
		font-size: 0.95rem;
		font-weight: 600;
		margin-bottom: 6px;
	}

	input[type="text"] {
		width: 100%;
		box-sizing: border-box;
		padding: 11px 12px;
		border-radius: 10px;
		border: 1px solid #b9c0c5;
		font-size: 1rem;
		font-family: 'Space Grotesk', sans-serif;
		background: #ffffff;
	}

	button {
		border: 0;
		border-radius: 10px;
		padding: 11px 14px;
		font-size: 0.95rem;
		font-weight: 700;
		font-family: 'Space Grotesk', sans-serif;
		cursor: pointer;
	}

	.btn-add {
		background: var(--primary);
		color: #ffffff;
	}

	.btn-add:hover {
		background: var(--primary-hover);
	}

	.btn-clear {
		margin-top: 12px;
		background: var(--danger);
		color: #ffffff;
	}

	.btn-clear:hover {
		background: var(--danger-hover);
	}

	.message-list {
		list-style: none;
		padding: 0;
		margin: 0;
		display: grid;
		gap: 10px;
	}

	.message-item {
		padding: 12px;
		border-radius: 12px;
		border: 1px solid var(--border);
		background: var(--bg-soft);
		display: flex;
		justify-content: space-between;
		gap: 12px;
		align-items: baseline;
	}

	.message-text {
		font-weight: 500;
	}

	.message-meta {
		font-family: 'IBM Plex Mono', monospace;
		font-size: 0.8rem;
		color: #5f676d;
		white-space: nowrap;
	}

	@media (max-width: 680px) {
		.app {
			margin: 18px auto;
			padding: 14px;
		}

		.form-grid {
			grid-template-columns: 1fr;
		}

		.message-item {
			flex-direction: column;
			align-items: flex-start;
		}
	}
</style>

<main class="app">
	<section class="hero">
		<h1>Bienvenue sur mon back PHP</h1>
		<p class="subtitle">Connexion Azure OK. Tu peux maintenant enregistrer des donnees.</p>

		<?php if ($feedback !== ""): ?>
		<div class="feedback <?= htmlspecialchars($feedbackType, ENT_QUOTES, 'UTF-8') ?>">
			<?= htmlspecialchars($feedback, ENT_QUOTES, 'UTF-8') ?>
		</div>
		<?php endif; ?>
	</section>

	<section class="panel">
		<h2>Ajouter un message</h2>
		<form method="post">
			<input type="hidden" name="action" value="add">
			<div class="form-grid">
				<div>
					<label for="content">Nouveau message :</label>
					<input type="text" id="content" name="content" maxlength="255" required>
				</div>
				<button type="submit" class="btn-add">Ajouter</button>
			</div>
		</form>

		<form method="post" onsubmit="return confirm('Supprimer tous les messages ?');">
			<input type="hidden" name="action" value="clear">
			<button type="submit" class="btn-clear">Vider la base</button>
		</form>
	</section>

	<section class="panel">
		<h2>Derniers messages</h2>
		<?php if (count($messages) === 0): ?>
		<p>Aucun message en base pour le moment.</p>
		<?php else: ?>
		<ul class="message-list">
			<?php foreach ($messages as $message): ?>
			<li class="message-item">
				<span class="message-text">#<?= (int)$message['id'] ?> - <?= htmlspecialchars($message['content'], ENT_QUOTES, 'UTF-8') ?></span>
				<span class="message-meta"><?= htmlspecialchars($message['created_at'], ENT_QUOTES, 'UTF-8') ?></span>
			</li>
			<?php endforeach; ?>
		</ul>
		<?php endif; ?>
	</section>
</main>
