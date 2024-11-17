<?php

include 'header.php';
require_once '../utils/db_connection.php';

use Utils\DbConnection;

echo "<div class='accueil'>";

if (isset($_SESSION['user_id'])) {
    $utilisateur_id = $_SESSION['user_id'];

    $db = new DbConnection();
    if ($db->connect()) {
        $stmt = $db->getConnection()->prepare("SELECT nom FROM utilisateurs WHERE id = ?");
        $stmt->execute([$utilisateur_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            echo "<p class='username'>Connecté en tant que : <strong>" . htmlspecialchars($user['nom']) . "</strong></p>";
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['idee_id'], $_POST['vote'])) {
                $idee_id = (int)$_POST['idee_id'];
                $vote = (int)$_POST['vote'];

                $userVoteStmt = $db->getConnection()->prepare("SELECT 1 FROM votes WHERE idee_id = ? AND utilisateur_id = ?");
                $userVoteStmt->execute([$idee_id, $utilisateur_id]);

                if ($userVoteStmt->fetch()) {
                    $updateVoteStmt = $db->getConnection()->prepare("UPDATE votes SET vote = ? WHERE idee_id = ? AND utilisateur_id = ?");
                    $updateVoteStmt->execute([$vote, $idee_id, $utilisateur_id]);
                } else {
                    $insertVoteStmt = $db->getConnection()->prepare("INSERT INTO votes (idee_id, utilisateur_id, vote) VALUES (?, ?, ?)");
                    $insertVoteStmt->execute([$idee_id, $utilisateur_id, $vote]);
                }
            }
        }
	// Récupérer le nom de l'utilisateur connecté
	$utilisateur_id = $_SESSION['user_id'];
	
	// Créer une instance de connexion et se connecter
	$db = new DbConnection();
	if ($db->connect()) {
		// Récupérer les informations de l'utilisateur
		$stmt = $db->getConnection()->prepare("SELECT nom FROM utilisateurs WHERE id = ?");
		$stmt->execute([$utilisateur_id]);
		$user = $stmt->fetch(PDO::FETCH_ASSOC);

		// Afficher le nom de l'utilisateur
		if ($user) {
			echo "<p class='username'>Connecté en tant que : " . "<strong> " . htmlspecialchars($user['nom']) . "</strong>" . "</p>";
		}

		// Gestion du vote si le formulaire est soumis
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			// Vérifier si c'est un vote
			if (isset($_POST['idee_id'], $_POST['vote'])) {
				// Vérifier si l'utilisateur est connecté
				if (!isset($_SESSION['user_id'])) {
					// Si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
					header('Location: authPage.php');
					exit();
				}

				// Récupérer l'ID de l'utilisateur connecté
				$utilisateur_id = $_SESSION['user_id'];
				$idee_id = (int)$_POST['idee_id'];
				$vote = (int)$_POST['vote']; // 1 pour upvote, 0 pour downvote
			}
		}

		echo "<h1>Liste des idées</h1>";

        $stmt = $db->getConnection()->prepare("
            SELECT idees.id, idees.titre, idees.description, utilisateurs.nom 
            FROM idees
            JOIN utilisateurs ON idees.utilisateur_id = utilisateurs.id
        ");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($result)) {
            echo "<ul>";
            foreach ($result as $row) {
                $idee_id = $row['id'];

                $upvotesStmt = $db->getConnection()->prepare("SELECT COUNT(*) FROM votes WHERE idee_id = ? AND vote = 1");
                $upvotesStmt->execute([$idee_id]);
                $upvotes = $upvotesStmt->fetchColumn();

                $downvotesStmt = $db->getConnection()->prepare("SELECT COUNT(*) FROM votes WHERE idee_id = ? AND vote = 0");
                $downvotesStmt->execute([$idee_id]);
                $downvotes = $downvotesStmt->fetchColumn();

                $userVoteStmt = $db->getConnection()->prepare("SELECT vote FROM votes WHERE idee_id = ? AND utilisateur_id = ?");
                $userVoteStmt->execute([$idee_id, $utilisateur_id]);
                $userVote = $userVoteStmt->fetch(PDO::FETCH_ASSOC);

                $voteStatus = $userVote ? "vote effectué" : "vote disponible";

                echo "<div class='idea'>";
                echo "<p class='vote_status'>" . $voteStatus . "</p>";
                echo "<li>";
                echo "<strong>" . htmlspecialchars($row['titre']) . "</strong><br>";
                echo htmlspecialchars($row['description']) . "<br>";
                echo "<em>Proposé par : " . htmlspecialchars($row['nom']) . "</em>";
                echo "</li>";

?>
                <div class="form_thumbs">
                    <form method="POST">
                        <input type="hidden" name="idee_id" value="<?php echo $idee_id; ?>">
                        <div class="upblock">
                            <button type="submit" name="vote" value="1" class="up"><i></i></button>
                            <span class="vote_count"><?php echo $upvotes; ?></span>
                        </div>
                        <div class="downblock">
                            <button type="submit" name="vote" value="0" class="down"><i></i></button>
                            <span class="vote_count"><?php echo $downvotes; ?></span>
                        </div>
                    </form>
                </div>
<?php
		// Préparer et exécuter la requête pour obtenir les idées avec les noms des utilisateurs
		$stmt = $db->getConnection()->prepare("
			SELECT idees.id, idees.titre, idees.description, utilisateurs.nom 
			FROM idees
			JOIN utilisateurs ON idees.utilisateur_id = utilisateurs.id
		");
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		// Vérifier et afficher les résultats
		if (!empty($result)) {
			echo "<ul>";
			foreach ($result as $row) {
				echo "<div class='idea'>";
				$idee_id = $row['id'];
				echo "<li>";
				echo "<strong>" . htmlspecialchars($row['titre']) . "</strong><br>";
				echo htmlspecialchars($row['description']) . "<br>";
				echo "<em>Proposé par : " . htmlspecialchars($row['nom']) . "</em>";
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

				echo "</div>";
			}
			echo "</ul>";
		} else {
			echo "<p>Aucune idée trouvée.</p>";
		}

        $db->disconnect();
    } else {
        echo "<p>Erreur de connexion à la base de données.</p>";
    }
		// Déconnexion
		$db->disconnect();
	} else {
		echo "<p>Erreur de connexion à la base de données.</p>";
	}
} else {
	echo "<p>Non connecté.</p>";
}

echo "</div>";
include('footer.php');
?>

include 'footer.php';
?>
