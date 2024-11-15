<?php

use Models\Idea;
use Utils\DbConnection;

include('header.php');

$titre = null;
$message = null;
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = filter_input(INPUT_POST, "titre");
    $message = filter_input(INPUT_POST, "message");
    $db = new DbConnection();
    $connection = $db->getConnection();
    Idea::create($connection, $_SESSION["user_id"], $titre, $message);
    $db->disconnect();
}
?>
<!-- Contenu principal de la page d'accueil ici -->

<!-- <h1>Création de l'idée</h1> -->

<!DOCTYPE html>
<html>
    <head>
        <!-- utf-8 sur la page -->
        <meta charset="utf-8">

        <!-- Titre de la page -->
        <title> Créer mon idée</title>


    </head>

    <body>

        <!-- formulaire en POST pour recupérer les données -->
        <form action="" method="POST" class="formulaire">

            <div class="formulaire">
                <label for="titre">Votre titre: </label>
                <input type="text" name="titre" id="titre" required />
            </div>

            <div class="formulaire">
                <label for="message">Votre message :</label>
                <!--Max length de 65 535 car le type text de mysql a cettee limite de charactères -->
                <textarea cols="" rows="" name="message" maxlength="65530"></textarea>
            </div>

            <div class="formulaire">
                <input type="submit" value="Envoyer !" />
            </div>

        </form>

    </body>
</html>

<?php include('footer.php'); ?>