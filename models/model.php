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

	abstract public static function getAll(PDO &$connection): array|null;
	// abstract public static function getById(PDO &$connection, int $id);
	// abstract public static function create(PDO &$connection): Model|null;
	abstract public function insert(PDO &$connection): bool;
	abstract public function update(PDO &$connection): bool;
	abstract public function delete(PDO &$connection): bool;
	abstract public static function eraseById(PDO &$connection, int $id): bool;
}