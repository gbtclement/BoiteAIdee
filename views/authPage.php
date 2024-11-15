<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentification</title>
</head>
<body>

    <form method="POST">
        <label for="username">Nom d'utilisateur :</label>
        <input type="text" name="username" id="username" required>
        <button type="submit">Comparer</button>
    </form>

    <?php
        include('../utils/db_connection-test.php');

        use Utils\DbConnection;
        
        // Créer une instance de connexion et se connecter
        $db = new DbConnection();
        if ($db->connect()) {
            echo "<h1>Bienvenue</h1>";
            echo "<h2>Liste des idées</h2>";

            if ($_SERVER("REQUEST_METHOD") == "post") {

                $inputUsername = $_POST['username'];
                $stmt = $db->getConnection()->prepare("SELECT nom FROM utilisateurs WHERE nom = :username");
                $stmt->bindParam(':username', $inputUsername, PDO::PARAM_STR);
                $stmt->execute();
                
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($result)) {
                    echo "utilisateur trouvé !";
                }else{
                    echo "cette utilisateur n'existe pas !";
                }
            }

            // Déconnexion
            $db->disconnect();
        } else {
            echo "<p>Erreur de connexion à la base de données.</p>";
        }

    ?>

</body>
</html>
