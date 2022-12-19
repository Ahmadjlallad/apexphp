<?php

namespace Apex\src\Database;

use Apex\src\App;
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

    public function appleyMigration()
    {
        $this->createMigrationsTable();
        $test = $this->getAppliedMigration();

        $files = scandir(App::$ROOT_DIR . '/migrations');
        dd($files, $test);
    }

    public function createMigrationsTable(): void
    {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (id INT AUTO_INCREMENT PRIMARY KEY, migration VARCHAR(255), create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=INNODB;");
    }

    private function getAppliedMigration(): bool|array
    {
        $statement = $this->pdo->prepare("SELECT * FROM migrations");
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }
}