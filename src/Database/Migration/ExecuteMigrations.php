<?php

namespace Apex\src\Database\Migration;

use Apex\src\App;
use Apex\src\Database\Migration\Schema\Builder;
use Apex\src\Database\Migration\Schema\Definition\Column;
use PDO;
use PDOException;

/**
 * Execute Migrations
 * @todo implement down method
 */
class ExecuteMigrations
{
    /**
     * @var array
     */
    public array $newMigrations = [];

    /**
     * @param PDO $pdo
     * @param Builder $builder
     */
    public function __construct(public PDO $pdo, private readonly Builder $builder)
    {
    }

    /**
     * @return void
     */
    public function applyMigration(): void
    {
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigration();
        $migrationsFile = App::$ROOT_DIR . '/migrations';
        $files = array_diff(scandir($migrationsFile), ['.', '..']);
        $files = array_map(fn($file) => pathinfo($file, PATHINFO_FILENAME), $files);
        $toApplyMigrations = array_diff($files, $appliedMigrations);
        foreach ($toApplyMigrations as $migration) {
            $this->newMigrations[] = $migration;
            infoLog("Applying Migration $migration");
            $currentMigration = $_ENV['MIGRATIONS_NAME_SPACE'] . '\\' . $migration;
            try {
                /** @var Migration $currentMigration */
                $currentMigration = new $currentMigration($this->pdo, $this->builder);
                $currentMigration->up();
            } finally {
                try {
                    $currentMigration->save();
                } catch (PDOException $PDOException) {
                    dd($PDOException->errorInfo);
                }
            }
            infoLog("Applied Migration $migration");
        }
        if (!empty($this->newMigrations)) {
            $this->saveMigrations($this->newMigrations);
        } else {
            infoLog("All Migrations has been Applied");
        }
    }

    /**
     * @return void
     */
    public function createMigrationsTable(): void
    {
        try {
            $r = $this->builder->create('migrations', [
                'id' => Column::add()->primaryKey(),
                'migration' => Column::add()->text(),
                'created_at' => Column::add()->timestamps()
            ], true);
            $this->pdo->exec($r);
        } catch (PDOException $exception) {
            dd($exception);
        }
    }

    /**
     * @return bool|array
     */
    private function getAppliedMigration(): bool|array
    {
        $statement = $this->pdo->prepare("SELECT migration FROM migrations");
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }


    /**
     * @param array $migrations
     * @return void
     */
    private function saveMigrations(array $migrations): void
    {
        $migrations = implode(', ', array_map(fn($migration) => "('$migration', now())", $migrations));
        $statement = $this->pdo->prepare("INSERT INTO migrations (migration, created_at) VALUES $migrations");
        $statement->execute();
    }
}