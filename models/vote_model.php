<?php

namespace Models;

use PDO;
use PDOException;

class Vote extends Model
{
	private int $user_id;
	private User $User;
	private int $idea_id;
	private Idea $Idea;
	private bool $vote;
	private string $creation_date;

#region getters
	public function getId (): int {
		return $this->id;
	}

	public function getUserId (): int {
		return $this->user_id;
	}

	public function getUser (): User {
		return $this->User;
	}

	public function getIdeaId(): int {
		return $this->idea_id;
	}

	public function getIdea(): Idea {
		return $this->Idea;
	}

	public function getVote (): bool {
		return $this->vote;
	}
	
	public function getCreation_date(): string {
		return $this->creation_date;
	}

#endregion
	

#region
	public function setId (int $id): void {
		$this->id = $id;
	}

	public function setUserId (int $user_id): void {
		$this->user_id = $user_id;
	}

	public function setUser (User $User): void {
		$this->User = $User;
	}

	public function setIdeaId(int $idea_id): void {
		$this->idea_id = $idea_id;
	}

	public function setIdea(Idea $Idea): void {
		$this->Idea = $Idea;
	}

	public function setVote (bool $vote): void {
		$this->vote = $vote;
	}
	
	public function setCreation_date(string $creation_date): void {
		$this->creation_date = $creation_date;
	}

#endregion

	/**
	 * Rècupère tous les votes
	 * @param \PDO $connection
	 * @return array|null Tableau 1D de tous les votes ou null en cas d'erreur
	 */
        static function getAll(PDO &$connection) : array|null {
		$req = $connection->prepare("
			SELECT * FROM votes
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

		$votes = [];

		foreach ($req->fetchAll() as $vote) {
			$Vote = new Vote();
			$Vote->setIdeaId($vote["id"]);
			$Vote->setUserId($vote["name"]);
			array_push($votes, $Vote);
		}

		return $votes;
	}

	/**
	 * Rècupère le vote à partir de l'id
	 * @param \PDO $connection
	 * @param int $id l'id du vote recherché
	 * @return \Models\Vote|null
	 */
	static function getById(PDO &$connection, int $id): Vote|null {
		$req = $connection->prepare("
			SELECT * FROM votes WHERE votes.id = :id
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

		$vote = $req->fetch();

		$Vote = new Vote();
		$Vote->setId($vote["id"]);
		$Vote->setUserId($vote["user_id"]);
		$Vote->setIdeaId($vote["idea_id"]);
		$Vote->setVote($vote["vote"]);
		$Vote->setCreation_date($vote["date_creation"]);

		return $Vote;
	}

	/**
	 * Insère un utilisateur et le retourne en tant qu'objet
	 * @param \PDO $connection la connection pdo
	 * @param string $user_id id de l'utilisateur votant
	 * @param string $idea_id id de l'idée votée
	 * @return \Models\Vote|null retourne le vote créé ou null en cas d'echec
	 */
	static function create(PDO &$connection, int $user_id, int $idea_id, bool $vote, string $creation_date): Vote|null {
		$req = $connection->prepare("
			INSERT INTO votes (user_id, idea_id, vote, date_creation) VALUES (:user_id, :idea_id, :vote, :creation_date)
		");

		$req->bindValue("user_id", $user_id, PDO::PARAM_STR);
		$req->bindValue("idea_id", $idea_id, PDO::PARAM_STR);
		$req->bindValue("vote", $vote, PDO::PARAM_STR);
		$req->bindValue("date_creation", $creation_date, PDO::PARAM_STR);

		$result = false;
		try {
			$result = $req->execute();
		} catch (PDOException $e) {
			echo htmlentities("une erreur est arrivée lors de la requete 
			(user_id = $user_id, idea_id = $idea_id, vote = $vote, date_creation = $creation_date), error : \n<br> $e");
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

		return Vote::getById($connection, $id);
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