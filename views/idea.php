<?php
include('header.php');
include('../utils/db_connection.php');

$utilisateur_id = $_SESSION["user"]["id"];

$db = new \Utils\DbConnection();
$db->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = htmlspecialchars($_POST['titre']);
    $message = htmlspecialchars($_POST['message']);

    $sql = "INSERT INTO idees (utilisateur_id, titre, description) VALUES (:utilisateur_id, :titre, :description)";
    $stmt = $db->getConnection()->prepare($sql);
    $stmt->bindParam(':utilisateur_id', $utilisateur_id);
    $stmt->bindParam(':titre', $titre);
    $stmt->bindParam(':description', $message);

    if ($stmt->execute()) {
        echo "<p>Votre idée a été enregistrée avec succès !</p>";
    } else {
        echo "<p>Une erreur est survenue lors de l'enregistrement de votre idée.</p>";
    }
}

?>

<div class="idea">
    <form action="idea.php" method="POST" class="formulaire">
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

<?php include('footer.php');?>
