<?php

namespace Models;

abstract class Model
{
	protected const TABLE_NAME = "abstract";
	protected int $id;


	abstract public function getId(): int;
	abstract public function setId(int $id);
}