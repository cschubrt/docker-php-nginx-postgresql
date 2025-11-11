<?php
namespace App;

class Database
{
    /**
     * Try to create and return a PDO connection to PostgreSQL.
     * Returns null if connection fails.
     *
     * Environment variables used (fallbacks included):
     *  POSTGRES_HOST / DB_HOST (default: db)
     *  POSTGRES_PORT / DB_PORT (default: 5432)
     *  POSTGRES_DB / DB (default: appdb)
     *  POSTGRES_USER (default: appuser)
     *  POSTGRES_PASSWORD (default: secret)
     */
    public static function getPdo(): ?\PDO
    {
        $host = getenv('POSTGRES_HOST') ?: getenv('DB_HOST') ?: 'db';
        $port = getenv('POSTGRES_PORT') ?: getenv('DB_PORT') ?: '5432';
        $db   = getenv('POSTGRES_DB') ?: getenv('DB') ?: 'appdb';
        $user = getenv('POSTGRES_USER') ?: 'appuser';
        $pass = getenv('POSTGRES_PASSWORD') ?: 'secret';

        $dsn = "pgsql:host={$host};port={$port};dbname={$db}";

        try {
            $pdo = new \PDO($dsn, $user, $pass, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
            return $pdo;
        } catch (\Throwable $e) {
            // Return null when DB is not reachable; tests can skip in that case.
            return null;
        }
    }
}
