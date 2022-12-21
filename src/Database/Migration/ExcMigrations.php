<?php

namespace Apex\src\Database\Migration;

use Apex\src\App;
use PDO;
use Symfony\Component\VarDumper\VarDumper;

class ExcMigrations
{
    public function __construct(public PDO $pdo)
    {
    }

    public array $newMigrations = [];

    public function applyMigration(): void
    {
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigration();
        $migrationsFile = App::$ROOT_DIR . '/migrations';
        $files = array_diff(scandir($migrationsFile), ['.', '..']);
        $files = array_map(fn($file) => pathinfo($file, PATHINFO_FILENAME), $files);
        $toApplyMigrations = array_diff($files, $appliedMigrations);
        foreach ($toApplyMigrations as $migration) {
            if ($migration === '.' || $migration === '..') continue;
            $this->newMigrations[] = $migration;
            $this->log("Applying Migration $migration");
            (new ($_ENV['MIGRATIONS_NAME_SPACE'] . '\\' . $migration)($this->pdo))->up();
            $this->log("Applied Migration $migration");
        }
        if (!empty($this->newMigrations)) {
//            $this->saveMigrations($this->newMigrations);
        } else {
            $this->log("All Migrations has been Applied");
        }
    }

    public function createMigrationsTable(): void
    {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (id INT AUTO_INCREMENT PRIMARY KEY, migration VARCHAR(255), create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=INNODB;");
    }

    private function getAppliedMigration(): bool|array
    {
        $statement = $this->pdo->prepare("SELECT migration FROM migrations");
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }

    private function log(string $message): void
    {
        VarDumper::dump(sprintf("INFO AT [%s] - %s", date('y-m-d H:i:s'), $message));
    }

    private function saveMigrations(array $migrations): void
    {
        $migrations = implode(', ', array_map(fn($migration) => "('$migration')", $migrations));
        $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES $migrations");
        $statement->execute();
    }
}