<?php 
include('header.php');
include('../utils/db_connection.php');

// Préparation et exécution de la requête SQL
//$stmt = $???->prepare("SELECT titre, description FROM idees");
//$stmt->execute();
//$result = $stmt->get_result();

echo "<h1>Bienvenue</h1>";
echo "<h2>Liste des idées</h2>";

// Affichage des idées
if ($result->num_rows > 0) {
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li>";
        echo "<strong>" . htmlspecialchars($row['titre']) . "</strong><br>";
        echo htmlspecialchars($row['description']);
        echo "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>Aucune idée trouvée.</p>";
}

include('footer.php'); 
?>
