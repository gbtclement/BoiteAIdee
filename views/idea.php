<?php

use Models\Idea;
use Utils\DbConnection;

include 'header.php';

$titre = null;
$message = null;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$titre = filter_input(INPUT_POST, "titre");
	$message = filter_input(INPUT_POST, "message");

	$db = new DbConnection();
	$connection = $db->getConnection();
	Idea::create($connection, $_SESSION["user_id"], $titre, $message);
	$db->disconnect();
}
?>
<div class="idea">
	<form action="" method="POST" class="formulaire">

		<div class="form-group row">
			<label for="titre">Votre titre: </label>
			<input type="text" name="titre" id="titre" required />
		</div>

		<div class="form-group row">
			<label for="message">Votre message :</label>
			<textarea cols="" rows="" name="message" maxlength="65530"></textarea>
		</div>

		<div class="form-group row">
			<input type="submit" value="Envoyer !" />
		</div>

	</form>

</div>

<?php include('footer.php'); ?>