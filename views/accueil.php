<?php
include 'header.php';
include '../utils/db_connection.php';

use Utils\DbConnection;
use Models\Vote;

if (!isset($_SESSION["user"]["id"])) {
	// Si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
	header('Location: authPage.php');
	exit();
}

echo "<div class='accueil'>";

// Créer une instance de connexion et se connecter
$db = new DbConnection();
if ($db->connect()) {
	// Gestion du vote si le formulaire est soumis
	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idee_id'], $_POST['vote'])) {
		// Récupérer l'ID de l'utilisateur connecté
		$utilisateur_id = $_SESSION["user"]["id"];
		$idee_id = (int)$_POST["idee_id"];
		$vote = (int)$_POST['vote']; // 1 pour upvote, 0 pour downvote

		// Vérifier si un vote existe déjà pour cet utilisateur et cette idée
		$stmt = $db->getConnection()->prepare("SELECT id FROM votes WHERE idee_id = :idee_id AND utilisateur_id = :utilisateur_id");
		$stmt->execute(['idee_id' => $idee_id, 'utilisateur_id' => $utilisateur_id]);
		$existingVote = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($existingVote) {
			// Si un vote existe déjà, mettre à jour le message et ne pas modifier le vote
			echo "<p>Vous avez déjà voté pour cette idée.</p>";
		} else {
			if (Vote::create($connection, $idee_id, $utilisateur_id, $vote) != null) {
				echo "<p>Votre vote a été pris en compte.</p>";
			}
		}
	}

	echo "<h1>Bienvenue</h1>";
	echo "<h2>Liste des idées</h2>";

	// Préparer et exécuter la requête pour obtenir les idées
	$stmt = $db->getConnection()->prepare("SELECT id, titre, description FROM idees");
	$stmt->execute();
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

	// Vérifier et afficher les résultats
	if (!empty($result)) {
		echo "<ul>";
		foreach ($result as $row) {
			$idee_id = $row['id'];
			echo "<li>";
			echo "<strong>" . htmlspecialchars($row['titre']) . "</strong><br>";
			echo htmlspecialchars($row['description']);
			echo "</li>";

			// Afficher les boutons de vote indépendamment de la connexion de l'utilisateur
			?>
			<div class="form_thumbs">
				<form method="POST">
					<input type="hidden" name="idee_id" value="<?php echo $idee_id; ?>">
					<button type="submit" name="vote" value="1" class="up"><i></i></button>
					<button type="submit" name="vote" value="0" class="down"><i></i></button>
				</form>
			</div>

			<?php
			// Vérifier si l'utilisateur est connecté et a déjà voté
			if (isset($_SESSION["user"]["id"])) {
				if (Vote::getByIdeaIdAndUserId($connection, $_SESSION["user"]["id"], $idee_id) != null) {
					// Si l'utilisateur a déjà voté, afficher un message et ne pas permettre un nouveau vote
					echo "<p>Vous avez déjà voté pour cette idée.</p>";
				}
			}
		}
		echo "</ul>";
	} else {
		echo "<p>Aucune idée trouvée.</p>";
	}

	// Déconnexion
	$db->disconnect();
} else {
	echo "<p>Erreur de connexion à la base de données.</p>";
}

echo "</div>";
include('footer.php');
?>
