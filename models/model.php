<?php

namespace Models;

use PDO;

abstract class Model
{
	protected int $id;

	public function getId(): int {
		return $this->id;
	}

	public function setId(int $id): void {
		$this->id;
	}

	abstract static public function getAll(PDO &$connection): array|null;
	// abstract static public function getById(PDO &$connection, int $id);
	// abstract static public function create(PDO &$connection);
	abstract public function insert(PDO &$connection): bool;
	abstract public function update(PDO &$connection): bool;
	abstract public function delete(PDO &$connection): bool;
}