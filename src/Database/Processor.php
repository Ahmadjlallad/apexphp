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

    public function query(string $query, array $params = [], $useAssoc = false): \PDOStatement
    {
        $statement = $this->connection->prepare($query);
        foreach ($params as $key => $param) {
            $statement->bindValue($useAssoc ? "$key" : '?', $param);
        }
        return $statement;
    }
}