<?php

namespace BxF\Db;

use PDO;

abstract class Adapter
{
    protected PDO $connection;
    
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }
    
    public abstract function query(string $query, array $params = []);
    
    public abstract function fetchAll(string $query, array $params = []);
}