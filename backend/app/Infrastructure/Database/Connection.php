<?php

namespace App\Infrastructure\Database;

use PDO;
use PDOException;

class Connection
{
    private PDO $pdo;

    public function __construct(array $config)
    {
        $dsn = sprintf('%s:host=%s;port=%s;dbname=%s;charset=%s',
            $config['driver'],
            $config['host'],
            $config['port'],
            $config['database'],
            $config['charset']
        );

        try {
            $this->pdo = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $exception) {
            throw new \RuntimeException('Database connection failed: ' . $exception->getMessage());
        }
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }
}
