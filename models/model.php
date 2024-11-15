<?php

namespace Models;

abstract class Model
{
	protected const TABLE_NAME = "abstract";
	protected int $id;

	public function getId(): int {
		return $this->id;
	}

	public function setId(int $id): void {
		$this->id;
	}
}