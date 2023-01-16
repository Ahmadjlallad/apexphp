<?php

namespace Apex\src\Database;

class Processor
{
    public function __construct(readonly private \PDO $connection)
    {
    }

    public function processUpdate($sql, array $bindings): bool
    {
        $statement = $this->process($sql);
        foreach ($statement as $i => $value) {
            $statement->bindValue($i + 1, $value);
        }
        return $statement->execute();
    }

    private function process($statement): bool|\PDOStatement
    {
        return $this->connection->prepare($statement);
    }
}