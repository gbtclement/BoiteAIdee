<?php

namespace Models;

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
		}

		if (!$result) {
			echo htmlentities("la requete à échouée");
			return null;
		}

		$users = [];

		foreach ($req->fetchAll() as $utilisateur) {
			$User = new User();
			$User->setId($utilisateur["id"]);
			$User->setName($utilisateur["name"]);
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
		}

		if (!$result) {
			echo htmlentities("la requete à échouée");
			return null;
		}

		$user = $req->fetch();

		$User = new User();
		$User->setId($user["id"]);
		$User->setName($user["name"]);

		return $User;
	}

	static function getByName(PDO &$connection, string $name): User|null {
		$req = $connection->prepare("
			SELECT * FROM utilisateurs WHERE utilisateurs.name = :name
		");

		$req->bindValue("name", $name, PDO::PARAM_STR);

		$result = false;
		try {
			$result = $req->execute();
		} catch (PDOException $e) {
			echo htmlentities("une erreur est arrivée lors de la requete, error : \n<br> $e");
		}

		if (!$result) {
			echo htmlentities("la requete à échouée");
			return null;
		}

		$user = $req->fetch();

		$User = new User();
		$User->setId($user["id"]);
		$User->setName($user["name"]);

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
			echo htmlentities("une erreur est arrivée lors de la requete (name = $name), error : \n<br> $e");
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
		return false;
	}

	public function update(PDO &$connection): bool {
		return false;
	}

	public function delete(PDO &$connection): bool {
		return false;
	}
}