<?php

//include '../utils/db_connection.php';
use Models\User;
use Utils\DbConnection;
use Utils\SessionHelper;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// si le formulaire est entré
	processForm();
}

<?php 
session_start(); // Démarre la session
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

    <?php
        include('../utils/db_connection.php');

        use Utils\DbConnection;
        
        $db = new DbConnection();
        if ($db->connect()) {
            // Gestion de la connexion
            if (isset($_POST["login"])) {
                $username = trim($_POST["signIn"]);
                $stmt = $db->getConnection()->prepare("SELECT id, nom FROM utilisateurs WHERE nom = :signIn");
                $stmt->bindParam(':signIn', $username, PDO::PARAM_STR);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
                if ($result) {
                    // Stocker l'utilisateur dans la session
                    $_SESSION['user_id'] = $result['id'];
                    $_SESSION['username'] = $result['nom'];
                    header("Location: accueil.php");
                    exit();
                } else {
                    echo "<p>Aucun utilisateur trouvé.</p>";
                }
            }

            // Gestion de la création de compte
            if (isset($_POST["createAccount"])) {
                $CreateUser = trim($_POST["signUp"]);

                if (empty($CreateUser)) {
                    echo "<p>Le champ ne peut pas être vide.</p>";
                } else {
                    $stmt = $db->getConnection()->prepare("SELECT nom FROM utilisateurs WHERE nom = :signUp");
                    $stmt->bindParam(':signUp', $CreateUser, PDO::PARAM_STR);
                    $stmt->execute();
                    $userExists = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($userExists) {
                        echo "<p>Utilisateur existe déjà !</p>";
                    } else {
                        $stmt = $db->getConnection()->prepare("INSERT INTO utilisateurs (nom) VALUES (:signUp)");
                        $stmt->bindParam(':signUp', $CreateUser, PDO::PARAM_STR);
                        if ($stmt->execute()) {
                            echo "<p>Utilisateur créé avec succès !</p>";
                        } else {
                            echo "<p>Erreur lors de la création de l'utilisateur.</p>";
                        }
                    }
                }
            }

            $db->disconnect();
        } else {
            echo "<p>Erreur de connexion à la base de données.</p>";
        }
    ?>

</body>
</html>
