<?php

require_once '../utils/db_connection.php';
use Models\User;
use Utils\DbConnection;
use Utils\SessionHelper;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// si le formulaire est entré
	processForm();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Authentification</title>
</head>

<body>

	<form method="POST">
		<label>Se connecter</label>
		<input type="text" name="signIn" required>
		<button type="submit" name="login">Vérifier</button>
	</form>

	<form method="POST">    
		<label>Créer un compte</label>
		<input type="text" name="signUp">
		<button type="submit" name="createAccount">Créer un compte</button>
	</form>

</body>
</html>


<?php

/**
 * Choisi le bon formulaire et vérifit s'il est bien remplit
 * @return bool en cas d'erreur retourne false sinon vrai
 */
function processForm(): bool {
	// connecte à la base et vérifit si la connection est valide
	$db = new DbConnection();
	$db->connect();

	if (!$db->isConnected()) {
		echo "<p>Impossible de ce connecter à la base de donnée !</p>";
		return false;
	}

	$connection = $db->getConnection();

	
	if (isset($_POST["login"])) {
		// vérifit le formulaire de connexion a un compte

		$signIn = filter_input(INPUT_POST, "signIn");
		if (empty($signIn)) {
			echo "<p>Le champ ne peut pas être vide.</p>";
			return false;
		}
		
		SessionHelper::logIn($connection, $signIn);

	} elseif (isset($_POST["createAccount"])) {
		// vérifit le formulaire de creation de compte

		$signUp = filter_input(INPUT_POST, "signUp");
		if (empty($signUp)) {
			echo "<p>Le champ ne peut pas être vide.</p>";
			return false;
		}

		SessionHelper::signUp($connection, $signUp);
	}
	
	$db->disconnect();
	return true;
}


?>