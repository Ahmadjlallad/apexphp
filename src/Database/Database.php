<?php

namespace Apex\src\Database;

use PDO;
use PDOException;

class Database
{
    public PDO $pdo;
    public function __construct(array $config)
    {
        $dsn = sprintf('%s:host=%s;port=%s;dbname=%s',
            $config['type'],
            $config['host'],
            $config['port'],
            $config['name'],
        );
        try {
            $this->pdo = new PDO($dsn, $config['user'], $config['password'], [PDO::ATTR_PERSISTENT => true]);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            dd($exception);
        }
    }


}