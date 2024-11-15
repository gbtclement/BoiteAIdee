<?php

namespace Utils;

use PDO;

class DbConnection
{
    private const DB_SERVICE = "mysql";
    private const DB_NAME = "boiteaidee";
    private const DB_HOST = "localhost";

    private string $username = "root";
    private string $password = "";
    
    private PDO|null $connection = null;

#region Getters
    public function getUsername(): string {
	return $this->username;
    }

    public function getPassword(): string {
	return $this->password;
    }

    private static function getDNS(): string {
	return self::DB_SERVICE . ":dbname=" . self::DB_NAME . ";host=" . self::DB_HOST;
    }

    public function getConnection(): PDO|null {
	return $this->connection;
    }
#endregion

#region Setters
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

#region Constructor and Connection Management
    function __construct(string|null $username = null, string|null $password = null) {
	$this->username ??= $username;
	$this->password ??= $password;
    }

    public function connect(): bool {
	try {
	    $this->connection = new PDO($this->getDNS(), $this->username, $this->password);
	    $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    return true;
	} catch (\PDOException $e) {
	    echo "Database connection failed: " . $e->getMessage();
	    return false;
	}
    }

    public function disconnect(): bool {
	$this->connection = null;
	return true;
    }

    public function isConnected(): bool {
	return $this->connection !== null;
    }
#endregion
}