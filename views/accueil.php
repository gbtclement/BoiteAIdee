<?php 
include('header.php');
include('../utils/db_connection_test_clement.php');

use Utils\DbConnection;

// Créer une instance de connexion et se connecter
$db = new DbConnection();
if ($db->connect()) {
    echo "<h1>Bienvenue</h1>";
    echo "<h2>Liste des idées</h2>";

    // Préparer et exécuter la requête
    $stmt = $db->getConnection()->prepare("SELECT titre, description FROM idees");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Vérifier et afficher les résultats
    if (!empty($result)) {
        echo "<ul>";
        foreach ($result as $row) {
            echo "<li>";
            echo "<strong>" . htmlspecialchars($row['titre']) . "</strong><br>";
            echo htmlspecialchars($row['description']);
            echo "</li>";
            echo "<div class='flex'>";
            echo "<i></i>";
            echo "<i></i>";
            echo "</div>";
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

include('footer.php'); 
?>
