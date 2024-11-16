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
		$this->id = $id;
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
			SELECT * FROM idees
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

		$ideas = [];

		foreach ($req->fetchAll() as $idee) {
			$Idea = new Idea();
			$Idea->setId($idee["id"]);
			$Idea->setUtilisateur_id($idee["user_id"]);
			$Idea->setTitre($idee["title"]);
			$Idea->setDescription($idee["description"]);
			$Idea->setDateCreation($idee["creation_date"]);
			array_push($ideas, $Idea);
		}

		return $ideas;
	}

	static function getById(PDO &$connection, int $id): Idea|null {
		$req = $connection->prepare("
			SELECT * FROM idees WHERE idees.id = :id
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

		$idea = $req->fetch();

		$Idea = new Idea();
		$Idea->setId($idea["id"]);
		$Idea->setUtilisateur_id($idea["user_id"]);
		$Idea->setTitre($idea["title"]);
		$Idea->setDescription($idea["description"]);
		$Idea->setDateCreation($idea["creation_date"]);
		return $Idea;
	}

	/**
	 * Insère un utilisateur et le retourne en tant qu'objet
	 * @param \PDO $connection la connection pdo
	 * @param string $name le nom de l'utilisateur
	 * @return \Models\Idea|null retourne l'idea créer ou null si la création est un echec
	 */
	static function create(PDO &$connection, int $user_id, string $title, string $description): Idea|null {
		$req = $connection->prepare("
			INSERT INTO idees (utilisateur_id, titre, description) VALUES (:user_id, :title, :description)");

		$req->bindValue("user_id", $user_id, PDO::PARAM_INT);
		$req->bindValue("title", $title, PDO::PARAM_STR);
		$req->bindValue("description", $description, PDO::PARAM_STR);

		$result = false;
		try {
			$result = $req->execute();
		} catch (PDOException $e) {
			echo htmlentities("une erreur est arrivée lors de la requete (user_id = $user_id,
			description = $description, title = $title), error : \n<br> $e");
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

	function insert(PDO &$conncetion): bool {
		return false;
	}

	function update(PDO &$conncetion): bool {
		return false;
	}

	/**
	 * Supprime l'idée de la base de donnée en conservant l'instance de l'objet
	 * @param \PDO $connection
	 * @return bool retourne true si la supréssion est réussie sinon false
	 */
	public function delete(PDO &$connection): bool {
		if ($this->id >= 0) {
			// l'id dans la BDD ne peut pas être négatif
			return false;
		}

		$req = $connection->prepare("
			DELETE idees WHERE idees.id = :id
		");

		$req->bindValue("id", $this->id, PDO::PARAM_INT);

		$result = false;
		try {
			$result = $req->execute();
		} catch (PDOException $e) {
			echo htmlentities("une erreur est arrivée lors de la suppression de l'idée ".
					"(id = $this->id), error : \n<br> $e");
			return false;
		}

		return $result;
	}

	/**
	 * Efface une de la base de donnée à partir de son id
	 * @param \PDO $connection
	 * @param int $id id de l'idée à supprimée
	 * @return bool retourne true si l'éffacement est réussie sinon false
	 */
	public static function eraseById(PDO &$connection, int $id): bool {
		$req = $connection->prepare("
			DELETE idees WHERE idees.id = :id
		");

		$req->bindValue("id", $id, PDO::PARAM_INT);

		$result = false;
		try {
			$result = $req->execute();
		} catch (PDOException $e) {
			echo htmlentities("une erreur est arrivée lors de l'effacement de l'idée ".
					"(id = $id), error : \n<br> $e");
			return false;
		}

		return $result;
	}
}