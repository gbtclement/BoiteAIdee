<?php

namespace Utils;

require_once "../models/user_model.php";

use \PDO;
use \Models\User;

class SessionHelper
{
	/**
	 * Connecte l'utilisateur
	 * @param PDO $connection
	 * @param string $name le nom de l'utilisateur
	 * @return bool retourne true si l'utilisateur est connecté sinon false
	 */
	public static function logIn(PDO &$connection, string $name): bool {
		$User = User::getByName($connection, $name);

		if ($User === null) {
			echo "<p>Aucun utilisateur trouvé.</p>";
			return false;
		}

		session_start();

		// ajoute les données de l'utilisateur dans la session
		$_SESSION["user"] = [
			"id" => $User->getId(),
			"user" => $User->getName()
		];

		return true;
	}


	/**
	 * Créer le compte de l'utilisateur
	 * @param PDO $connection
	 * @param string $name le nom de l'utilisateur
	 * @return bool retourne true si l'utilisateur est créé sinon false
	 */
	public static function signUp(PDO &$connection, string $name): bool {
		if (User::getByName($connection, $name) != null) {
			echo "<p>Utilisateur existe déjà !</p>";
			return false;
		}
	
		if (!User::create($connection, $name)) {
			echo "<p>Erreur lors de la création de l'utilisateur.</p>";
			return false;
		}
	
		echo "<p>Utilisateur créé avec succès !</p>";
		return true;
	}


	/**
	 * Déconnecte l'utilisateur
	 * @return bool
	 */
	public static function logOut(): bool {
		session_abort();
		return true;
	}

}