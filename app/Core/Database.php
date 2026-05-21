<?php

namespace App\Core;

use PDO;
use PDOException;

final class Database
{
    private array $dbConfig;
    private ?PDO $pdo = null;

    public function __construct(array $dbConfig)
    {
        $this->dbConfig = $dbConfig;
    }

    public function pdo()
    {
        if ($this->pdo instanceof PDO) {
            return $this->pdo;
        }

        $host = $this->dbConfig['host'];
        $port = (int)$this->dbConfig['port'];
        $db = $this->dbConfig['name'];
        $charset = $this->dbConfig['charset'] ?? 'utf8mb4';

        $dsn = "mysql:host={$host};port={$port};dbname={$db};charset={$charset}";

        $this->pdo = new PDO($dsn, $this->dbConfig['user'], $this->dbConfig['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        return $this->pdo;
    }

    public function pdoWithoutDatabase()
    {
        $host = $this->dbConfig['host'];
        $port = (int)$this->dbConfig['port'];
        $charset = $this->dbConfig['charset'] ?? 'utf8mb4';
        $dsn = "mysql:host={$host};port={$port};charset={$charset}";

        return new PDO($dsn, $this->dbConfig['user'], $this->dbConfig['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }

    public function createDatabaseIfNotExists()
    {
        $dbName = $this->dbConfig['name'];
        $pdo = $this->pdoWithoutDatabase();
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    }

    public function resetConnection()
    {
        $this->pdo = null;
    }

    public function isAvailable()
    {
        try {
            $this->pdo()->query('SELECT 1');
            return true;
        } catch (PDOException) {
            return false;
        }
    }
}
