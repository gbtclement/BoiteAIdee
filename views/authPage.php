<?php 
session_start(); // Démarre la session
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
            top: 20%;
            left: 0%;
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


    require_once('../utils/db_connection.php');
    use Utils\DbConnection;

$db = new DbConnection();
if ($db->connect()) {
    if (isset($_POST["login"])) {
        $username = $_POST["signIn"];
        $stmt = $db->getConnection()->prepare("SELECT nom FROM utilisateurs WHERE nom = :signIn");
        $stmt->bindParam(':signIn', $username, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        if (!empty($result)) {
            header("Location: accueil.php");  
        } else {
            echo "<p>Aucun utilisateur trouvée.</p>";
        }
    }
    if (isset($_POST["createAccount"])) {
        $CreateUser = trim($_POST["signUp"]);
        if (empty($CreateUser)) {
            echo "<p>Le champ ne peut pas être vide.</p>";
        } else {
        $stmt = $db->getConnection()->prepare("SELECT nom FROM utilisateurs WHERE nom = :signUp");
        $stmt->bindParam(':signUp', $CreateUser, PDO::PARAM_STR);
        $stmt->execute();
        $userExists = $stmt->fetch(PDO::FETCH_ASSOC);
        if($userExists) {
            echo "<p>Utilisateur existe déjà !</p>";
        }else {
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