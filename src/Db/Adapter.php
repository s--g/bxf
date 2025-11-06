<?php declare(strict_types = 1);

namespace BxF\Db;

use BxF\Application;
use BxF\Plugin\BootstrapPlugin;
use BxF\PropertyAccess;
use BxF\Registry;
use PDO;

/**
 * @method PDO getConnection()
 * @method $this setConnection(PDO $value)
 */
abstract class Adapter
    implements BootstrapPlugin
{
    use PropertyAccess;
    
    protected PDO $connection;
    
    public function __construct(?PDO $connection = null)
    {
        $this->connection = $connection;
    }
    
    public abstract function query(string $query, array $params = []): void;
    
    public abstract function fetchAll(string $query, array $params = []): false|array;
    
    public abstract function fetchRow(string $query, array $params = []): ?\stdClass;
    
    public abstract function fetchValue(string $query, array $params = []): string|int;
    
    public function onBootstrap(Application $application):bool
    {
        Registry::get()->setDb($this);
        return true;
    }
}