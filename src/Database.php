<?php

namespace App;

class Database
{
    private $pdo;

    /**
     * Try to create and return a PDO connection to PostgreSQL.
     * Returns null if connection fails.
     */
    public function __construct()
    {
        $dsn  = $this->getDsn();
        $user = getenv('POSTGRES_USER') ?: getenv('POSTGRES_USER') ?: 'appuser';
        $pass = getenv('POSTGRES_PASSWORD') ?: getenv('POSTGRES_PASSWORD') ?: 'apppassword';

        try {
            $this->pdo = new \PDO($dsn, $user, $pass, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
            return $this->pdo;
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function getDsn(): string
    {
        $host = getenv('POSTGRES_HOST') ?: getenv('DB_HOST') ?: 'db';
        $port = getenv('POSTGRES_PORT') ?: getenv('DB_PORT') ?: '5432';
        $db   = getenv('POSTGRES_DB') ?: getenv('DB') ?: 'appdb';

        return "pgsql:host={$host};port={$port};dbname={$db}";
    }

    public function isConnected(): \PDO
    {
        $pdo = $this->pdo;
        if ($pdo === null) {
            throw new \RuntimeException('Could not connect to the database.');
        }
        return $pdo;
    }

    public function getResults($query): array
    {
        $pdo = $this->isConnected();
        try {
            $stmt = $pdo->prepare($query);
            $stmt->execute();
        } catch (\PDOException $e) {
            throw new \RuntimeException('Database query error: ' . $e->getMessage());
        }
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

}
