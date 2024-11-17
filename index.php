<?php

if (!isset($_SESSION["user"]["id"])) {
	// Si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
	header('Location: views/authPage.php');
	exit();
}

// Redirige vers accueil.php
header("Location: views/accueil.php");
exit();