<?php

namespace Models;
use PDO;
use PDOException;

class Idea extends Model
{
	const TABLE_NAME = "idees";
	private $user_id;
	private $User;
	private $title;
	private $description;
	private $creation_date;

	public function getId(): int {
		return $this->id;
	}
	public function getUtilisateur_id(int $user_id): int {
		return $this->user_id;
	}
	public function getUtilisateur(User $User): int {
		return $this->User;
	}
	public function getTitre(): string {
		return $this->title;
	}
	public function getDescription(): string {
		return $this->description;
	}
	public function getDateCreation(): string {
		return $this->creation_date;
	}
	

	public function setId(int $id): void {
		$this->id;
	}
	public function setUtilisateur_id(int $user_id): void {
		$this->user_id = $user_id;
	}
	public function setUtilisateur($User): void {
		$this->User = $User;
	}
	public function setTitre(string $title): void {
		$this->title = $title;
	}
	public function setDescription(string $description): void {
		$this->description = $description;
	}
	public function setDateCreation(string $creation_date): void {
		$this->creation_date = $creation_date;
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
			$User = $utilisateur["id"];
			$User = $utilisateur["name"];
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

	/**
	 * Insère un utilisateur et le retourne en tant qu'objet
	 * @param \PDO $connection la connection pdo
	 * @param string $name le nom de l'utilisateur
	 * @return \Models\Idea|null retourne l'idea créer ou null si la création est un echec
	 */
	static function create(PDO &$connection, string $name): Idea|null {
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

		return Idea::getById($connection, $id);
	}

	function insert(): bool {
		return false;
	}

	function update(): bool {
		return false;
	}

	function delete(): bool {
		return false;
	}
}
