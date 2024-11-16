<?php

namespace Models;
use PDO;
use PDOException;

class Idea extends Model
{
	const TABLE_NAME = "idees";
	private int $user_id;
	private User $User;
	private string $title;
	private string $description;
	private string $creation_date;

	public function getId(): int {
		return $this->id;
	}
	public function getUserId(): int {
		return $this->user_id;
	}
	public function getUser(): User {
		return $this->User;
	}
	public function getTitle(): string {
		return $this->title;
	}
	public function getDescription(): string {
		return $this->description;
	}
	public function getCreationDate(): string {
		return $this->creation_date;
	}
	

	public function setId(int $id): void {
		$this->id = $id;
	}
	public function setUser_id(int $user_id): void {
		$this->user_id = $user_id;
	}
	public function setUser(User $User): void {
		$this->User = $User;
	}
	public function setTitle(string $title): void {
		$this->title = $title;
	}
	public function setDescription(string $description): void {
		$this->description = $description;
	}
	public function setCreationDate(string $creation_date): void {
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
			return null;
		}

		if (!$result) {
			echo htmlentities("la requete à échouée");
			return null;
		}

		$ideas = [];

		foreach ($req->fetchAll() as $idee) {
			$Idea = new Idea();
			$Idea->setId($idee["id"]);
			$Idea->setUser_id($idee["user_id"]);
			$Idea->setTitle($idee["title"]);
			$Idea->setDescription($idee["description"]);
			$Idea->setCreationDate($idee["creation_date"]);
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
			return null;
		}

		if (!$result) {
			echo htmlentities("la requete à échouée");
			return null;
		}

		$idea = $req->fetch();

		$Idea = new Idea();
		$Idea->setId($idea["id"]);
		$Idea->setUser_id($idea["user_id"]);
		$Idea->setTitle($idea["title"]);
		$Idea->setDescription($idea["description"]);
		$Idea->setCreationDate($idea["creation_date"]);
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

		return Idea::getById($connection, $id);
	}

	function insert(PDO &$connection): bool {

		$req = $connection->prepare("
			INSERT INTO idees (utilisateur_id, titre, description) VALUES (:user_id, :title, :description)");

		$req->bindValue("user_id", $this->getUserId(), PDO::PARAM_INT);
		$req->bindValue("title", $this->getTitle(), PDO::PARAM_STR);
		$req->bindValue("description", $this->getDescription(), PDO::PARAM_STR);

		$result = false;
		try {
			$result = $req->execute();
		} catch (PDOException $e) {
			echo htmlentities("une erreur est arrivée lors de la requete ".
					"(user_id = ".$this->getUserId().",description = ".$this->getDescription().
					", title = ".$this->getTitle()."), error : \n<br> $e");
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

		return true;
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
					"(id = ".$this->getId()."), error : \n<br> $e");
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