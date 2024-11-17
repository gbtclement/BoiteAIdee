<?php

namespace Models;

require_once "model.php";

use PDO;
use PDOException;

class User extends Model
{
	private string $name;

	public function getId(): int {
		return $this->id;
	}
	public function getName(): string {
		return $this->name;
	}
	

	public function setId(int $id): void {
		$this->id = $id;
	}
	public function setName(string $name): void {
		$this->name = $name;
	}


	function __construct() {
		
	}


	static function getAll(PDO &$connection) : array|null {
		$req = $connection->prepare("
			SELECT * FROM utilisateurs
		");

		$result = false;
		try {
			$result = $req->execute();
		} catch (PDOException $e) {
			echo htmlentities("une erreur est arrivée lors de la requete, error : \n<br> $e");
			return null;
		}

		if (!$result) {
			echo htmlentities("la requete à échouée");
			return null;
		}

		$users = [];

		foreach ($req->fetchAll() as $utilisateur) {
			$User = new User();
			$User->setId($utilisateur["id"]);
			$User->setName($utilisateur["nom"]);
			array_push($users, $User);
		}

		return $users;
	}

	static function getById(PDO &$connection, int $id): User|null {
		$req = $connection->prepare("
			SELECT * FROM utilisateurs WHERE utilisateurs.id = :id
		");

		$req->bindValue("id", $id, PDO::PARAM_INT);

		$result = false;
		try {
			$result = $req->execute();
		} catch (PDOException $e) {
			echo htmlentities("une erreur est arrivée lors de la requete, error : \n<br> $e");
			return null;
		}

		if (!$result) {
			echo htmlentities("la requete à échouée");
			return null;
		}

		$user = $req->fetch(PDO::FETCH_ASSOC);

		$User = new User();
		$User->setId($user["id"]);
		$User->setName($user["nom"]);

		return $User;
	}

	static function getByName(PDO &$connection, string $name): User|null {
		$req = $connection->prepare("
			SELECT * FROM utilisateurs WHERE utilisateurs.nom = :name
		");

		$req->bindValue("name", $name, PDO::PARAM_STR);

		$result = false;
		try {
			$result = $req->execute();
		} catch (PDOException $e) {
			echo htmlentities("une erreur est arrivée lors de la requete, error : \n<br> $e");
			return null;
		}

		if (!$result) {
			echo htmlentities("la requete \"getByName\" à échouée");
			return null;
		}

		$user = $req->fetch(PDO::FETCH_ASSOC);

		if ($user === false) {
			echo htmlentities("la requete à échouée, impossible de fetch (name = $name)");
			return null;
		}

		$User = new User();
		$User->setId($user["id"]);
		$User->setName($user["nom"]);

		return $User;
	}

	/**
	 * Insère un utilisateur et le retourne en tant qu'objet
	 * @param \PDO $connection la connection pdo
	 * @param string $name le nom de l'utilisateur
	 * @return \Models\User|null retourne l'utilisateur créer ou null si la création est un echec
	 */
	static function create(PDO &$connection, string $name): User|null {
		$req = $connection->prepare("
			INSERT INTO utilisateurs (nom) VALUES (:name)
		");

		$req->bindValue("name", $name, PDO::PARAM_STR);

		$result = false;
		try {
			$result = $req->execute();
		} catch (PDOException $e) {
			echo htmlentities("une erreur est arrivée lors de la requete ".
					"(name = $name), error : \n<br> $e");
			return null;
		}

		if (!$result) {
			echo htmlentities("la requete à échouée");
			return null;
		}

		$id = $connection->lastInsertId();

		if ($id == false) {
			echo htmlentities("la requete à échouée");
			return null;
		}

		return User::getById($connection, $id);
	}

	public function insert(PDO &$connection): bool {
		$req = $connection->prepare("
			INSERT INTO utilisateurs (nom) VALUES (:name)
		");

		$req->bindValue("name", $this->getName(), PDO::PARAM_STR);

		$result = false;
		try {
			$result = $req->execute();
		} catch (PDOException $e) {
			echo htmlentities("une erreur est arrivée lors de la requete ".
					"(name = ".$this->getName()."), error : \n<br> $e");
			return false;
		}

		if (!$result) {
			echo htmlentities("la requete à échouée");
			return false;
		}

		$id = $connection->lastInsertId();

		if ($id == false) {
			echo htmlentities("la requete à échouée");
			return false;
		}

		$this->setId($id);
		
		return true;
	}

	public function update(PDO &$connection): bool {
		return false;
	}

	/**
	 * Supprime l'utilisateur de la base de donnée en conservant l'instance de l'objet
	 * @param \PDO $connection
	 * @return bool retourne true si la supréssion est réussie sinon false
	 */
	public function delete(PDO &$connection): bool {
		if ($this->id >= 0) {
			// l'id dans la BDD ne peut pas être négatif
			return false;
		}

		$req = $connection->prepare("
			DELETE utilisateurs WHERE utilisateurs.id = :id
		");

		$req->bindValue("id", $this->getId(), PDO::PARAM_INT);

		$result = false;
		try {
			$result = $req->execute();
		} catch (PDOException $e) {
			echo htmlentities("une erreur est arrivée lors de la suppression de l'utilisateur ".
					"(id = ".$this->getId()."), error : \n<br> $e");
			return false;
		}

		return $result;
	}

	/**
	 * Efface un utilisateur de la base de donnée à partir de son id
	 * @param \PDO $connection
	 * @param int $id id de l'utilisateur à éffacé
	 * @return bool retourne true si l'éffacement est réussi sinon false
	 */
	public static function eraseById(PDO &$connection, int $id): bool {
		$req = $connection->prepare("
			DELETE utilisateurs WHERE utilisateurs.id = :id
		");

		$req->bindValue("id", $id, PDO::PARAM_INT);

		$result = false;
		try {
			$result = $req->execute();
		} catch (PDOException $e) {
			echo htmlentities("une erreur est arrivée lors de l'effacement de l'utilisateur ".
					"(id = $id), error : \n<br> $e");
			return false;
		}

		return $result;
	}
}