<?php

namespace BxF\Db;

use BxF\PropertyAccess;
use PDO;

/**
 * @method PDO getConnection()
 * @method $this setConnection(PDO $value)
 */
abstract class Adapter
{
    use PropertyAccess;
    
    protected PDO $connection;
    
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }
    
    public abstract function query(string $query, array $params = []);
    
    public abstract function fetchAll(string $query, array $params = []);
    
    public abstract function fetchOne(string $query, array $params = []);
    
    public abstract function fetchValue(string $query, array $params = []);
}