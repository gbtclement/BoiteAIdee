<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'boiteaidee');

$pdo = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Vérification de la connexion
if ($pdo->connect_error) {
    die("Erreur de connexion : " . $pdo->connect_error);
}

?>
