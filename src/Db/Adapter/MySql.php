<?php

namespace BxF\Db\Adapter;

use BxF\Db\Adapter;

class MySql extends Adapter
{
    public function query(string $query, array $params = [])
    {
        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);
    }
    
    public function fetchAll(string $query, array $params = [])
    {
        $stmt = $this->connection->prepare($query);
        $stmt->setFetchMode(\PDO::FETCH_OBJ);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function fetchRow(string $query, array $params = [])
    {
        $stmt = $this->connection->prepare($query);
        $stmt->setFetchMode(\PDO::FETCH_OBJ);
        $stmt->execute($params);
        return $stmt->fetch();
    }
    
    public function fetchValue(string $query, array $params = [])
    {
        $stmt = $this->connection->prepare($query);
        $stmt->setFetchMode(\PDO::FETCH_OBJ);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }
}