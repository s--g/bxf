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
        $stmt->execute($params);
        return $stmt->fetch();
    }
}