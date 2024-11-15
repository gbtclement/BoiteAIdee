<?php

namespace Models;

class Idea extends Model
{
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
}
