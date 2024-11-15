<?php
// Vérifie si une session est déjà active avant de démarrer une nouvelle session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Boite à idées</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <header>
        <nav>
            <a href="index.php">Accueil</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="idea.php">Créer une idée</a>
            <?php else: ?>
                <a href="authPage.php">Connexion</a>
            <?php endif; ?>
        </nav>
    </header>
    <div class="content">