<?php

namespace App\Core;

use Exception;
use PDO;
use PDOException;

class Db
{
    private string $host = '';
    private int $port = 6379;
    private string $user = '';
    private string $pass = '';
    private string $db = '';
    public ?PDO $connection = null;

    public function __construct()
    {
        $this->setCredentials();

        $dsn = "mysql:host={$this->host};dbname={$this->db};port={$this->port}";

        try {
            $pdo = new PDO($dsn, $this->user, $this->pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection = $pdo;
        } catch (PDOException $e) {
            throw new Exception("PDO Connection failed: " . $e->getMessage());
        }
    }

    private function setCredentials(): void
    {
        $credentials = require(__DIR__ . '/../../db.php');

        $this->host = $credentials['host'];
        $this->port = $credentials['port'];
        $this->user = $credentials['user'];
        $this->pass = $credentials['pass'];
        $this->db = $credentials['db'];
    }

    public function getConnection(): ?PDO
    {
        return $this->connection;
    }
}
