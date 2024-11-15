<?php

namespace Utils;

use PDO;

class DbConnection
{
	private const DB_SERVICE = "mysql";
	private const DB_NAME = "";
	private const DB_HOST = "";
	private const DB_PORT = "";

	private string $username = "";
	private string $password = "";
	private PDO|null $connection = null;

#region getters 
	public function getUsername(): string {
		return $this->username;
	}

	public function getPassword(): string {
		return $this->password;
	}

	private static function getDNS(): string {
		return self::DB_SERVICE.":dbname=".self::DB_NAME.";host=".self::DB_HOST.";port=".self::DB_PORT;
	}

#endregion


#region setters 
	public function setUsername(string $username): void {
		if ($this->isConnected() === true) {
			return;
		}
		$this->username = $username;
	}

	public function setPassword(string $password): void {
		if ($this->isConnected() === true) {
			return;
		}
		$this->password = $password;
	}

#endregion

	function __construct(string|null $username = null, string|null $password = null) {
		$this->username ??= $username;
		$this->password ??= $password;
	}

	public function connect(): bool {
		try {
			$this->connection = new PDO($this->getDNS(), $this->username, $this->password);
			return $this->isConnected();
		} catch (\PDOException $e) {
			echo "Can't connect to database, error : \n<br>$e";
		}
		return false;
	}

	public function disconnect(): bool {
		$this->connection = null;
		return true;
	}

	public function isConnected(): bool {
		return $this->connection == null;
	}
}
