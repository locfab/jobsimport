<?php

namespace JobMangement\Database;

use Exception;
use PDO;

interface DatabaseConnectionInterface
{
    public function getConnection(): PDO;
}


class DatabaseConnection implements DatabaseConnectionInterface
{
    private PDO $connection;

    public function __construct(string $host, string $username, string $password, string $databaseName)
    {
        try {
            $this->connection = new PDO('mysql:host=' . $host . ';dbname=' . $databaseName, $username, $password);
        } catch (Exception $e) {
            die('DB error: ' . $e->getMessage() . "\n");
        }
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}

?>
