<?php declare(strict_types = 1);

namespace BxF\Db\Adapter;

use BxF\Db\Adapter;

class MySql
    extends Adapter
{
    public function query(string $query, array $params = []): void
    {
        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);
    }
    
    public function fetchAll(string $query, array $params = []): false|array
    {
        $stmt = $this->connection->prepare($query);
        $stmt->setFetchMode(\PDO::FETCH_OBJ);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function fetchRow(string $query, array $params = []): mixed
    {
        $stmt = $this->connection->prepare($query);
        $stmt->setFetchMode(\PDO::FETCH_OBJ);
        $stmt->execute($params);
        return $stmt->fetch();
    }
    
    public function fetchValue(string $query, array $params = []): string
    {
        $stmt = $this->connection->prepare($query);
        $stmt->setFetchMode(\PDO::FETCH_OBJ);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }
    
    public function lastInsertId(): false|string
    {
        return $this->connection->lastInsertId();
    }
}