<?php

require_once '../utils/db_connection.php';
require_once '../utils/session_helper.php';

use Utils\DbConnection;
use Utils\SessionHelper;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	processForm();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
	<title>Authentification</title>
</head>
<body>
	<div class="form-container">
	<form method="POST">
		<p>Se connecter</p>
		<div class="sign-container">
			<input type="text" name="signIn" required>
			<button type="submit" name="login">Se connecter</button>
		</div>
	</form>
	<p> </p>
	<form method="POST">
		<p>Créer un compte</p>
		<div class="sign-container">
			<input type="text" name="signUp">
			<button id="signUp" type="submit" name="createAccount">Créer un compte</button>
		</div>
	</form>
	</div>

	<style>
		body {
			background-color: #fbfbfb;
			position: absolute;
			left: 0%;
			top: 20%;
			justify-content: end;
			font-family: 'Roboto', sans-serif;
			
		}

		p {
			font-weight: 500px;
			font-size: 18px;
			margin: 0px;
		}

		.form-container {
			display: flex;
			flex-direction: column;
			justify-content: center;
			background-color: white;
			width: 280px;
			height: 320px;
			gap: 16px;
			padding: 28px;
			margin: 16px;
			border-radius: 20px;
			-webkit-box-shadow: 0px 5px 12px 3px rgba(0,0,0,0.6); 
			box-shadow: 0px 8px 12px 0px rgba(0,0,0,0.6);
		}

		.sign-container {
			display: flex;
			flex-direction: column;
			gap: 24px;
		}

		form {
			display: flex;
			flex-direction: column;
			gap: 8px
		}

		input {
			height: 28px;
			border: none;
			border-bottom: 1px solid black;
		}

		input:hover {
			border-bottom: 2px solid #1338BE;
		}

		button {
			height: 36px;
			color: white;
			border: none;
			border-radius: 8px;
			background-color: #1338BE;
		}

		button:hover {
			cursor: pointer;
			background-color: #1338AE;
		}

		#signUp {
			background-color: white;
			border: 1px solid black;
			color: black;
		}

		#signUp:hover {
			background-color: #eeeeee;
		} 
	</style>

	<?php





function processForm() {
	$db = new DbConnection();
	$db->connect();
	$connection = $db->getConnection();

	if (!$db->isConnected()) {
		echo "<p>Erreur de connexion à la base de données.</p>";
	}
	
	if (isset($_POST["login"])) {
		$username = filter_input(INPUT_POST, $_POST["signIn"]);
		
		if (SessionHelper::logIn($connection, $username)) {
			header("Location: accueil.php");  
		} else {
			echo "<p>Aucun utilisateur trouvée.</p>";
		}
	}
	
	elseif (isset($_POST["createAccount"])) {
		$username = filter_input(INPUT_POST, $_POST["signUp"]);

		if ($username === null or $username === false) {
			echo "<p>Le champ ne peut pas être vide.</p>";
		}

		if (SessionHelper::signUp($connection, $username)) {
			echo "<p>Utilisateur créé avec succès !</p>";
		} else {
			echo "<p>Erreur lors de la création de l'utilisateur.</p>";
		}
	}
	$db->disconnect();
}

?>





</body>
</html>
